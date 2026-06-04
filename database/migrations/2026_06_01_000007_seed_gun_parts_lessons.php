<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $lessonId = DB::table('lessons')->insertGetId([
            'key' => 'gun-parts',
            'title' => 'Marksmanship Subject Overview',
            'description' => 'Gun parts, safety, handling, and marksmanship simulation overview.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pages = [
            [
                'lesson_index' => 0,
                'page_index' => 0,
                'title' => 'Introduction of Marksmanship Subject',
                'body_html' => <<<'HTML'
<div class="grid md:grid-cols-[1.05fr_.95fr] gap-6 h-full min-h-0 items-center">
    <div>
        <p class="presentation-kicker mb-3">Introduction</p>
        <h3 class="presentation-title text-3xl sm:text-4xl mb-4">Introduction of Marksmanship Subject</h3>
        <p class="text-sm sm:text-base text-gray-600 leading-7 mb-4">This subject builds firearms knowledge in three concise lessons. Each lesson moves from understanding what a firearm is, to handling it safely, to applying those skills in a simulation range.</p>
        <div class="card p-4">
            <p class="text-xs uppercase tracking-widest text-violet-500 font-bold mb-2">Table of Contents</p>
            <ol class="space-y-2 text-sm text-gray-600">
                <li><span class="font-semibold text-gray-900">Lesson 1:</span> Firearm Introduction and Identification</li>
                <li><span class="font-semibold text-gray-900">Lesson 2:</span> Safety, Handling, Assembly and Disassembly</li>
                <li><span class="font-semibold text-gray-900">Lesson 3:</span> Marksmanship and Virtual Firing Range</li>
            </ol>
        </div>
    </div>
    <div class="rounded-3xl bg-violet-50 border border-violet-100 p-5 sm:p-6">
        <p class="text-[10px] uppercase tracking-[0.24em] text-violet-500 font-bold mb-4">Learning Flow</p>
        <div class="space-y-3">
            <div class="card p-4"><h4 class="font-bold text-gray-900">Identify</h4><p class="text-sm text-gray-600 mt-1">Know the firearm types and core parts.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Handle</h4><p class="text-sm text-gray-600 mt-1">Practice safety and procedural handling.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Perform</h4><p class="text-sm text-gray-600 mt-1">Apply marksmanship in simulation.</p></div>
        </div>
    </div>
</div>
HTML,
            ],
            [
                'lesson_index' => 0,
                'page_index' => 1,
                'title' => 'Firearm Overview and Weapon Types',
                'body_html' => <<<'HTML'
<div class="grid md:grid-cols-[1.05fr_.95fr] gap-6 h-full min-h-0 items-center">
    <div class="rounded-3xl bg-white border border-violet-100 p-5 sm:p-6 shadow-sm">
        <p class="presentation-kicker mb-3">Lesson 1</p>
        <h3 class="presentation-title text-3xl sm:text-4xl mb-4">Firearm Overview and Weapon Types</h3>
        <p class="text-sm sm:text-base text-gray-600 leading-7 mb-4">Lesson 1 defines what a firearm is, introduces safety concepts, and explains the three weapon types used in the system.</p>
        <ul class="space-y-2 text-sm text-gray-600">
            <li class="flex gap-3"><span class="mt-2 h-2 w-2 rounded-full bg-violet-500"></span><span>Firearm definition and classification basics.</span></li>
            <li class="flex gap-3"><span class="mt-2 h-2 w-2 rounded-full bg-violet-500"></span><span>General safety concept overview.</span></li>
            <li class="flex gap-3"><span class="mt-2 h-2 w-2 rounded-full bg-violet-500"></span><span>Weapon type comparison inside the system.</span></li>
        </ul>
    </div>
    <div class="grid gap-4">
        <div class="card p-4">
            <h4 class="font-bold text-gray-900 mb-1">9mm Pistol</h4>
            <p class="text-sm text-gray-600">Semi-auto, magazine-fed, common in law enforcement training.</p>
        </div>
        <div class="card p-4">
            <h4 class="font-bold text-gray-900 mb-1">.45 Caliber Pistol</h4>
            <p class="text-sm text-gray-600">Larger caliber impact, heavier recoil than 9mm.</p>
        </div>
        <div class="card p-4">
            <h4 class="font-bold text-gray-900 mb-1">.38 Revolver</h4>
            <p class="text-sm text-gray-600">Cylinder-based firing, simple and reliable mechanism.</p>
        </div>
    </div>
</div>
HTML,
            ],
            [
                'lesson_index' => 0,
                'page_index' => 2,
                'title' => 'Parts Identification',
                'body_html' => <<<'HTML'
<div class="grid lg:grid-cols-[1.05fr_.95fr] gap-6 h-full min-h-0 items-center">
    <div>
        <p class="presentation-kicker mb-3">Lesson 1</p>
        <h3 class="presentation-title text-3xl sm:text-4xl mb-4">Parts Identification</h3>
        <p class="text-sm sm:text-base text-gray-600 leading-7 mb-4">Students identify core parts for pistols and revolvers, then practice matching and labeling in the UI.</p>
        <div class="card p-4 mb-4">
            <p class="text-xs uppercase tracking-widest text-violet-500 font-bold mb-2">Core Parts</p>
            <ul class="grid sm:grid-cols-2 gap-2 text-sm text-gray-600">
                <li>Slide, barrel, frame</li>
                <li>Cylinder, hammer, trigger</li>
                <li>Magazine system vs cylinder system</li>
            </ul>
        </div>
        <div class="grid sm:grid-cols-2 gap-3">
            <div class="card p-4">
                <h4 class="font-bold text-gray-900 mb-1">Activities</h4>
                <p class="text-sm text-gray-600">Click-to-identify parts and drag labels to the correct area.</p>
            </div>
            <div class="card p-4">
                <h4 class="font-bold text-gray-900 mb-1">Assessment</h4>
                <p class="text-sm text-gray-600">Identification quizzes and timed labeling tests.</p>
            </div>
        </div>
    </div>
    <div class="rounded-3xl bg-violet-50 border border-violet-100 p-5 sm:p-6">
        <p class="text-[10px] uppercase tracking-[0.24em] text-violet-500 font-bold mb-4">Compare Types</p>
        <div class="space-y-3">
            <div class="card p-4"><h4 class="font-bold text-gray-900">Pistol Layout</h4><p class="text-sm text-gray-600 mt-1">Slide and magazine cycle each shot.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Revolver Layout</h4><p class="text-sm text-gray-600 mt-1">Cylinder indexes for each round.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Side-by-Side</h4><p class="text-sm text-gray-600 mt-1">Contrast frames, loading systems, and triggers.</p></div>
        </div>
    </div>
</div>
HTML,
            ],
            [
                'lesson_index' => 1,
                'page_index' => 3,
                'title' => 'Safety and Handling Procedures',
                'body_html' => <<<'HTML'
<div class="grid md:grid-cols-[1.1fr_.9fr] gap-6 h-full min-h-0 items-center">
    <div>
        <p class="presentation-kicker mb-3">Lesson 2</p>
        <h3 class="presentation-title text-3xl sm:text-4xl mb-4">Safety and Handling Procedures</h3>
        <div class="card p-4 mb-4">
            <p class="text-xs uppercase tracking-widest text-violet-500 font-bold mb-2">Universal Safety Rules</p>
            <ul class="space-y-2 text-sm text-gray-600">
                <li>Treat all firearms as loaded.</li>
                <li>Maintain muzzle and trigger discipline.</li>
                <li>Be aware of target and surroundings.</li>
            </ul>
        </div>
        <div class="card p-4">
            <p class="text-xs uppercase tracking-widest text-violet-500 font-bold mb-2">Safe Handling</p>
            <ul class="space-y-2 text-sm text-gray-600">
                <li>Pick up the firearm safely.</li>
                <li>Clear and check the chamber.</li>
                <li>Perform cylinder check for revolvers.</li>
                <li>Store firearms safely after use.</li>
            </ul>
        </div>
    </div>
    <div class="rounded-3xl bg-violet-50 border border-violet-100 p-5 sm:p-6">
        <p class="text-[10px] uppercase tracking-[0.24em] text-violet-500 font-bold mb-4">Focus</p>
        <div class="space-y-3">
            <div class="card p-4"><h4 class="font-bold text-gray-900">Procedural mastery</h4><p class="text-sm text-gray-600 mt-1">Learn the order and purpose of each safety step.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Consistent checks</h4><p class="text-sm text-gray-600 mt-1">Repeat the same safe handling process every time.</p></div>
        </div>
    </div>
</div>
HTML,
            ],
            [
                'lesson_index' => 1,
                'page_index' => 4,
                'title' => 'Field Stripping and Assembly',
                'body_html' => <<<'HTML'
<div class="grid lg:grid-cols-[1.05fr_.95fr] gap-6 h-full min-h-0 items-center">
    <div>
        <p class="presentation-kicker mb-3">Lesson 2</p>
        <h3 class="presentation-title text-3xl sm:text-4xl mb-4">Field Stripping and Assembly</h3>
        <div class="grid sm:grid-cols-2 gap-3">
            <div class="card p-4">
                <h4 class="font-bold text-gray-900 mb-2">9mm and .45 Pistols</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>Magazine removal and slide lock.</li>
                    <li>Slide disassembly and barrel removal.</li>
                    <li>Recoil spring removal.</li>
                </ul>
            </div>
            <div class="card p-4">
                <h4 class="font-bold text-gray-900 mb-2">.38 Revolver</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>Cylinder opening and inspection.</li>
                    <li>Limited disassembly for education.</li>
                </ul>
            </div>
        </div>
        <div class="card p-4 mt-4">
            <h4 class="font-bold text-gray-900 mb-2">Assembly Logic</h4>
            <p class="text-sm text-gray-600">Reverse sequence, validate placement, and detect errors in the simulation.</p>
        </div>
    </div>
    <div class="rounded-3xl bg-violet-50 border border-violet-100 p-5 sm:p-6">
        <p class="text-[10px] uppercase tracking-[0.24em] text-violet-500 font-bold mb-4">System Features</p>
        <div class="space-y-3">
            <div class="card p-4"><h4 class="font-bold text-gray-900">Guided assembly</h4><p class="text-sm text-gray-600 mt-1">Highlight the correct next part.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Lock incorrect placements</h4><p class="text-sm text-gray-600 mt-1">Prevent wrong part placement and track errors.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Step scoring</h4><p class="text-sm text-gray-600 mt-1">Score each step for accuracy and sequence.</p></div>
        </div>
        <div class="card p-4 mt-3">
            <h4 class="font-bold text-gray-900 mb-1">Assessment</h4>
            <p class="text-sm text-gray-600">Procedure accuracy score and sequence completion test.</p>
        </div>
    </div>
</div>
HTML,
            ],
            [
                'lesson_index' => 2,
                'page_index' => 5,
                'title' => 'Marksmanship Fundamentals',
                'body_html' => <<<'HTML'
<div class="grid md:grid-cols-[1.1fr_.9fr] gap-6 h-full min-h-0 items-center">
    <div>
        <p class="presentation-kicker mb-3">Lesson 3</p>
        <h3 class="presentation-title text-3xl sm:text-4xl mb-4">Marksmanship Fundamentals</h3>
        <div class="card p-4 mb-4">
            <p class="text-xs uppercase tracking-widest text-violet-500 font-bold mb-2">Core Mechanics</p>
            <ul class="space-y-2 text-sm text-gray-600">
                <li>Proper stance: isosceles or modified.</li>
                <li>Grip technique for pistol and revolver.</li>
                <li>Sight alignment and sight picture.</li>
            </ul>
        </div>
        <div class="card p-4">
            <p class="text-xs uppercase tracking-widest text-violet-500 font-bold mb-2">Trigger and Breathing</p>
            <ul class="space-y-2 text-sm text-gray-600">
                <li>Smooth trigger press and reset awareness.</li>
                <li>Breathing rhythm with a pause before shot.</li>
            </ul>
        </div>
    </div>
    <div class="rounded-3xl bg-violet-50 border border-violet-100 p-5 sm:p-6">
        <p class="text-[10px] uppercase tracking-[0.24em] text-violet-500 font-bold mb-4">Target Engagement</p>
        <div class="space-y-3">
            <div class="card p-4"><h4 class="font-bold text-gray-900">Aiming discipline</h4><p class="text-sm text-gray-600 mt-1">Focus on sights and target alignment.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Controlled firing</h4><p class="text-sm text-gray-600 mt-1">Manage recoil and keep shots grouped.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Shot grouping</h4><p class="text-sm text-gray-600 mt-1">Track tightness for accuracy scoring.</p></div>
        </div>
    </div>
</div>
HTML,
            ],
            [
                'lesson_index' => 2,
                'page_index' => 6,
                'title' => 'Virtual Firing Range and Scoring',
                'body_html' => <<<'HTML'
<div class="grid lg:grid-cols-[1.05fr_.95fr] gap-6 h-full min-h-0 items-center">
    <div>
        <p class="presentation-kicker mb-3">Lesson 3</p>
        <h3 class="presentation-title text-3xl sm:text-4xl mb-4">Virtual Firing Range and Scoring</h3>
        <div class="grid sm:grid-cols-2 gap-3">
            <div class="card p-4">
                <h4 class="font-bold text-gray-900 mb-2">Simulation Modes</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>9mm: controlled semi-auto firing.</li>
                    <li>.45: higher recoil, lower stability.</li>
                    <li>.38: cylinder-based firing rhythm.</li>
                </ul>
            </div>
            <div class="card p-4">
                <h4 class="font-bold text-gray-900 mb-2">Scoring System</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>Accuracy percentage and time.</li>
                    <li>Shot grouping tightness.</li>
                    <li>Safety violations penalty.</li>
                </ul>
            </div>
        </div>
        <div class="card p-4 mt-4">
            <h4 class="font-bold text-gray-900 mb-1">Assessment</h4>
            <p class="text-sm text-gray-600">Timed qualification, accuracy challenge, and a 75% minimum score.</p>
        </div>
    </div>
    <div class="rounded-3xl bg-violet-50 border border-violet-100 p-5 sm:p-6">
        <p class="text-[10px] uppercase tracking-[0.24em] text-violet-500 font-bold mb-4">Final System Logic</p>
        <div class="space-y-3">
            <div class="card p-4"><h4 class="font-bold text-gray-900">Lesson 1</h4><p class="text-sm text-gray-600 mt-1">Knowledge: What is the firearm?</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Lesson 2</h4><p class="text-sm text-gray-600 mt-1">Procedure: How to handle it safely.</p></div>
            <div class="card p-4"><h4 class="font-bold text-gray-900">Lesson 3</h4><p class="text-sm text-gray-600 mt-1">Application: Use it in simulation.</p></div>
        </div>
        <div class="card p-4 mt-3">
            <h4 class="font-bold text-gray-900 mb-1">Why this works</h4>
            <p class="text-sm text-gray-600">The 3-lesson structure supports drag and drop assembly, 2D/3D views, and IoT firing integration.</p>
        </div>
    </div>
</div>
HTML,
            ],
        ];

        $now = now();

        foreach ($pages as $page) {
            DB::table('lesson_pages')->insert([
                'lesson_id' => $lessonId,
                'lesson_index' => $page['lesson_index'],
                'page_index' => $page['page_index'],
                'title' => $page['title'],
                'body_html' => $page['body_html'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $lesson = DB::table('lessons')->where('key', 'gun-parts')->first();

        if ($lesson) {
            DB::table('lesson_pages')->where('lesson_id', $lesson->id)->delete();
            DB::table('lessons')->where('id', $lesson->id)->delete();
        }
    }
};
