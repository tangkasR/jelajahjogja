<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TripPlannerController extends Controller {

    public function index() {
        $categories = Category::all();
        return view('public.trip-planner', compact('categories'));
    }

    public function generate(Request $request) {

        $user = $request->user();
        if ($user) {
            $quota = (int) ($user->trip_plan_quota ?? 2);
            $generated = (int) ($user->trip_plan_generated_count ?? 0);

            if ($generated >= $quota) {
                return back()->with('ai_error', 'Kuota generate trip kamu sudah habis.');
            }
        }

        set_time_limit(240);
        $request->validate([
            'duration'   => 'required|integer|min:1|max:7',
            'budget'     => 'required|in:hemat,sedang,mewah',
            'interests'  => 'required|array|min:1',
            'travelers'  => 'required|in:solo,pasangan,keluarga,rombongan',
            'extra_note' => 'nullable|string|max:300',
        ]);


        // Ambil semua destinasi approved dari DB
        $destinations = Destination::approved()
        ->with('category')
        ->get()
        ->map(fn($d) => [
            's' => $d->slug,
            'n' => $d->title,
            'k' => $d->category->name,
            'w' => $d->district,
            'r' => $d->averageRating(),
        ]);
        // $destinations = Destination::approved()
        //     ->with(['category', 'hero'])
        //     ->get()
        //     ->map(fn($d) => [
        //         'id'       => $d->id,
        //         'nama'     => $d->title,
        //         'kategori' => $d->category->name,
        //         'wilayah'  => $d->district,
        //         'deskripsi'=> substr($d->description, 0, 150),
        //         'rating'   => $d->averageRating(),
        //         'slug'     => $d->slug,
        //     ]);

        $budgetLabel = [
            'hemat'  => 'Budget hemat (backpacker, gratis atau murah)',
            'sedang' => 'Budget menengah (Rp 300-700rb/hari)',
            'mewah'  => 'Budget premium (tidak masalah soal harga)',
        ][$request->budget];

        $travelerLabel = [
            'solo'       => 'Solo traveler',
            'pasangan'   => 'Berdua (pasangan)',
            'keluarga'   => 'Keluarga dengan anak',
            'rombongan'  => 'Rombongan/grup',
        ][$request->travelers];

        $interestList = implode(', ', $request->interests);
        $extraNote    = $request->extra_note ? "Catatan tambahan: {$request->extra_note}" : '';

        // Untuk mencegah prompt terlalu panjang saat user memilih semua opsi,
        // kita limit data destinasi yang dikirim ke AI.
        // Mapping minat -> nama Category di database.
        $interestToCategory = [
            'alam' => 'Alam',
            'budaya' => 'Budaya',
            'sejarah' => 'Sejarah',
            'kuliner' => 'Kuliner',
            'belanja' => 'Belanja',
            'foto' => 'Fotografi',
            'religi' => 'Religi',
            'petualangan' => 'Petualangan',
        ];

        $selectedCategories = collect($request->interests)
            ->map(fn($i) => $interestToCategory[$i] ?? null)
            ->filter()
            ->values();

        $maxDestinationsForPrompt = 60;

        // Ambil destinasi hanya yang relevan berdasarkan category (jika ada)
        // lalu limit untuk menghindari AI error.
        $destinationsForPromptQuery = Destination::approved()
            ->with('category');

        if ($selectedCategories->count() > 0) {
            $destinationsForPromptQuery->whereHas('category', function ($q) use ($selectedCategories) {
                $q->whereIn('name', $selectedCategories);
            });
        }

        $destinations = $destinationsForPromptQuery
            ->get()
            ->sortByDesc(fn($d) => $d->averageRating())
            ->take($maxDestinationsForPrompt)
            ->map(fn($d) => [
                's' => $d->slug,
                'n' => $d->title,
                'k' => $d->category->name,
                'w' => $d->district,
                'r' => $d->averageRating(),
            ]);

        // Jika ternyata query relevan menghasilkan terlalu sedikit data (mis. category tidak ada),
        // fallback ambil top destinasi berdasarkan rating.
        if ($destinations->count() < 5) {
            $destinations = Destination::approved()
                ->with('category')
                ->get()
                ->sortByDesc(fn($d) => $d->averageRating())
                ->take($maxDestinationsForPrompt)
                ->map(fn($d) => [
                    's' => $d->slug,
                    'n' => $d->title,
                    'k' => $d->category->name,
                    'w' => $d->district,
                    'r' => $d->averageRating(),
                ]);
        }

        $prompt = <<<PROMPT
        Kamu adalah travel planner profesional Yogyakarta. Buatkan perencanaan wisata Yogyakarta yang detail, efisien, dan realistis.

        DATA DESTINASI TERSEDIA DI DATABASE:
        {$destinations->toJson(JSON_PRETTY_PRINT)}

        INPUT USER:
        - Durasi: {$request->duration} hari
        - Tipe traveler: {$travelerLabel}
        - Minat/preferensi: {$interestList}
        - Budget: {$budgetLabel}
        {$extraNote}

        INSTRUKSI WAJIB:
        1. Buat perencanaan {$request->duration} hari yang REALISTIS dan EFISIEN
        2. WAJIB gunakan destinasi dari database — gunakan slug yang PERSIS sama
        3. Setiap hari maksimal 3-4 destinasi, jangan terlalu padat
        4. EFISIENSI RUTE — ini sangat penting:
           - Kelompokkan destinasi yang berdekatan secara geografis dalam 1 hari
           - Urutkan destinasi dalam 1 hari dari yang paling barat/utara ke timur/selatan atau searah
           - Hindari bolak-balik arah yang tidak perlu
           - Pertimbangkan koordinat lat/lng untuk memastikan urutan logis
        5. KONTINUITAS ANTAR HARI:
           - Hari berikutnya mulai dari area yang dekat dengan akhir hari sebelumnya
           - Destinasi terakhir hari N harus dekat dengan destinasi pertama hari N+1
           - Ini membuat perjalanan mengalir natural tanpa perjalanan jauh di pagi hari
        6. Sertakan koordinat lat/lng yang AKURAT untuk setiap destinasi
        7. Estimasi transportasi realistis antar destinasi berdasarkan jarak sebenarnya
        8. Rekomendasikan penginapan di lokasi STRATEGIS (tengah-tengah area kunjungan)
        9. Sesuaikan dengan budget dan tipe traveler
        10. Gunakan bahasa Indonesia yang hangat

        FORMAT WAJIB — balas HANYA dengan JSON valid, tanpa teks lain:
        {
          "summary": "Ringkasan rencana (2-3 kalimat, sebutkan area yang dikunjungi per hari)",
          "strategi_rute": "Penjelasan singkat strategi rute keseluruhan — kenapa urutan ini efisien",
          "tips_umum": ["tip1", "tip2", "tip3"],
          "estimasi_total": {
            "biaya_min": "Rp 500.000",
            "biaya_max": "Rp 800.000",
            "catatan": "Belum termasuk penginapan"
          },
          "penginapan": [
            {
              "nama": "Nama Hotel/Penginapan",
              "tipe": "Hotel/Hostel/Guest House/Villa",
              "bintang": 3,
              "kisaran_harga": "Rp 200.000 - 350.000/malam",
              "lokasi": "Nama area/jalan",
              "lat": -7.797068,
              "lng": 110.370529,
              "fasilitas": ["WiFi", "AC", "Sarapan"],
              "cocok_untuk": "Solo traveler dan pasangan",
              "alasan": "Strategis karena dekat area kunjungan hari 1 dan 2"
            }
          ],
          "hari": [
            {
              "hari_ke": 1,
              "tema": "Tema hari ini",
              "area_utama": "Nama area/wilayah utama hari ini",
              "destinasi": [
                {
                  "slug": "slug-dari-database",
                  "nama": "Nama destinasi",
                  "lat": -7.797068,
                  "lng": 110.370529,
                  "waktu_mulai": "08:00",
                  "waktu_selesai": "10:00",
                  "durasi": "2 jam",
                  "tips": "Tips spesifik",
                  "estimasi_biaya": "Rp 10.000 - 25.000",
                  "transportasi_ke_berikutnya": {
                    "moda": "Ojek Online",
                    "jarak": "5 km",
                    "waktu_tempuh": "15 menit",
                    "estimasi_biaya": "Rp 12.000 - 18.000",
                    "arah": "ke selatan menuju pantai"
                  }
                }
              ],
              "kuliner_rekomendasi": {
                "nama": "Nama tempat makan",
                "menu": "Menu rekomendasi",
                "kisaran_harga": "Rp 15.000 - 30.000",
                "lat": -7.797068,
                "lng": 110.370529,
                "waktu": "Makan siang 12:00"
              },
              "estimasi_biaya_hari": {
                "min": "Rp 150.000",
                "max": "Rp 250.000",
                "rincian": "Tiket masuk + makan + transport"
              },
              "titik_akhir": {
                "nama": "Nama destinasi terakhir hari ini",
                "lat": -7.797068,
                "lng": 110.370529
              },
              "catatan_hari": "Catatan tips untuk hari ini"
            }
          ]
        }
        PROMPT;



        // groq ai
        try {
            set_time_limit(360);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->timeout(240)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => 'llama-3.3-70b-versatile',
                'max_tokens'  => 4000,
                'temperature' => 0.7,
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'Kamu adalah travel planner profesional Yogyakarta. Balas HANYA dengan JSON valid tanpa teks lain, tanpa markdown, tanpa kode block.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if ($response->failed()) {
                return back()->with('ai_error', 'Gagal menghubungi AI. Code: ' . $response->status());
            }

            $content = $response->json('choices.0.message.content');

            if (empty($content)) {
                return back()->with('ai_error', 'Respon AI kosong. Coba lagi.');
            }

            // Bersihkan markdown jika ada
            $content = preg_replace('/```json\s*/i', '', $content);
            $content = preg_replace('/```\s*/i', '', $content);
            $content = trim($content);

            // Ambil JSON dari dalam string jika ada teks sebelum/sesudah
            if (!str_starts_with($content, '{')) {
                preg_match('/\{.*\}/s', $content, $matches);
                $content = $matches[0] ?? $content;
            }

            $itinerary = json_decode($content, true);

            if (!$itinerary || !isset($itinerary['hari'])) {
                return back()->with('ai_error', 'Format respon AI tidak valid. Coba lagi.');
            }

            // Inject data destinasi dari DB
            $destMap = Destination::approved()->with('hero')->get()->keyBy('slug');

            foreach ($itinerary['hari'] as &$hari) {
                foreach ($hari['destinasi'] as &$dest) {
                    $dbDest = $destMap->get($dest['slug'] ?? '');
                    if ($dbDest) {
                        $dest['url']      = route('destinations.show', $dest['slug']);
                        $dest['hero_url'] = $dbDest->hero ? asset('storage/' . $dbDest->hero->path) : null;
                        $dest['wilayah']  = $dbDest->district;
                        $dest['found']    = true;
                    } else {
                        $dest['url']      = null;
                        $dest['hero_url'] = null;
                        $dest['found']    = false;
                    }
                }
            }

            // Increment quota counter hanya jika generate berhasil & valid
            if ($user) {
                $user->increment('trip_plan_generated_count');
            }

            session(['trip_itinerary' => $itinerary, 'trip_input' => $request->all()]);

            return redirect()->route('trip-planner.result');

        } catch (\Exception $e) {
            return back()->with('ai_error', 'Terjadi kesalahan: ' . $e->getMessage());
        }


        // claude ai
        //  try {
        //     // claude ai
        //     $response = Http::withHeaders([
        //         'x-api-key'         => config('services.anthropic.key'),
        //         'anthropic-version' => '2023-06-01',
        //         'content-type'      => 'application/json',
        //     ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
        //         'model'      => 'claude-sonnet-4-5',
        //         'max_tokens' => 4000,
        //         'messages'   => [
        //             ['role' => 'user', 'content' => $prompt],
        //         ],
        //     ]);
        //     if ($response->failed()) {
        //         return back()->with('ai_error', 'Gagal menghubungi AI. Coba lagi.');
        //     }
        //     $content = $response->json('content.0.text');
        //     // Bersihkan jika ada markdown code block
        //     $content = preg_replace('/```json\s*/i', '', $content);
        //     $content = preg_replace('/```\s*/i', '', $content);
        //     $content = trim($content);
        //     $itinerary = json_decode($content, true);
        //     if (!$itinerary || !isset($itinerary['hari'])) {
        //         return back()->with('ai_error', 'Respon AI tidak valid. Coba lagi.');
        //     }
        //     // Inject data destinasi dari DB ke itinerary (foto, url, dll)
        //     $destMap = Destination::approved()
        //         ->with('hero')
        //         ->get()
        //         ->keyBy('slug');
        //     foreach ($itinerary['hari'] as &$hari) {
        //         foreach ($hari['destinasi'] as &$dest) {
        //             $dbDest = $destMap->get($dest['slug']);
        //             if ($dbDest) {
        //                 $dest['url']      = route('destinations.show', $dest['slug']);
        //                 $dest['hero_url'] = $dbDest->hero
        //                     ? asset('storage/' . $dbDest->hero->path)
        //                     : null;
        //                 $dest['wilayah']  = $dbDest->district;
        //                 $dest['found']    = true;
        //             } else {
        //                 $dest['url']      = null;
        //                 $dest['hero_url'] = null;
        //                 $dest['found']    = false;
        //             }
        //         }
        //     }
        //     // Simpan ke session untuk re-render
        //     session(['trip_itinerary' => $itinerary, 'trip_input' => $request->all()]);
        //     return redirect()->route('trip-planner.result');
        // } catch (\Exception $e) {
        //     return back()->with('ai_error', 'Terjadi kesalahan: ' . $e->getMessage());
        // }
    }

    public function result() {
        $itinerary = session('trip_itinerary');
        $tripInput = session('trip_input');

        if (!$itinerary) {
            return redirect()->route('trip-planner');
        }

        return view('public.trip-planner-result', compact('itinerary', 'tripInput'));
    }
}