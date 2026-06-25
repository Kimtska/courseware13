<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\LessonPage;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleContentSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'module_key' => 'module-1',
                'title' => 'Gun Parts & Systems',
                'description' => 'A comprehensive guide to understanding firearm components, weapon types, and identification of critical gun parts used across different weapon systems.',
                'sort_order' => 1,
            ],
            [
                'module_key' => 'module-2',
                'title' => 'Marksmanship & Firing Techniques',
                'description' => 'This module focuses on the fundamentals of marksmanship including firing principles, proper stance and grip, and trigger control.',
                'sort_order' => 2,
            ],
            [
                'module_key' => 'module-3',
                'title' => 'Weapon Maintenance & Safety',
                'description' => 'This module covers proper weapon maintenance procedures, cleaning techniques, common malfunction identification, and safe storage and transportation practices.',
                'sort_order' => 3,
            ],
        ];

        foreach ($modules as $data) {
            Module::firstOrCreate(['module_key' => $data['module_key']], $data);
        }

        $lessons = [
            // ======================== MODULE 1 ========================
            [
                'lesson_key' => 'module-1-intro',
                'module_key' => 'module-1',
                'title' => 'Introduction to Firearms',
                'description' => 'Module 1 introduction covering the fundamental concepts of firearms including weapon types, parts identification, safety protocols, and ammunition basics.',
                'sort_order' => 1,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => 'Gun Parts & Systems',
                        'body_html' => <<<'HTML'
<div class="flex flex-col justify-center items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-2xl mx-auto text-left">
        <p class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3">Introduction</p>
        <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 mb-6">Gun Parts & Systems</h1>
        <p class="text-xl text-gray-700 mb-8 leading-relaxed">
            A comprehensive guide to understanding firearm components, weapon types, and identification of critical gun parts used across different weapon systems.
        </p>
        <div class="flex flex-col gap-4">
            <p class="text-lg text-gray-600">This course covers:</p>
            <ul class="space-y-3">
                <li class="flex gap-3 items-center">
                    <i class="fas fa-check text-violet-500"></i>
                    <span class="text-gray-700">Firearm overview and safety concepts</span>
                </li>
                <li class="flex gap-3 items-center">
                    <i class="fas fa-check text-violet-500"></i>
                    <span class="text-gray-700">Weapon types and their characteristics</span>
                </li>
                <li class="flex gap-3 items-center">
                    <i class="fas fa-check text-violet-500"></i>
                    <span class="text-gray-700">Parts identification and functionality</span>
                </li>
            </ul>
        </div>
    </div>
</div>
HTML
                    ],
                    [
                        'page_index' => 1,
                        'title' => 'Module 1 Introduction',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-3xl w-full text-center">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 1</span>
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-3xl"><i class="fas fa-gun"></i></div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Introduction to Firearms</h1>
        <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">This module covers the fundamental concepts of firearms including weapon types, parts identification, safety protocols, and ammunition basics. By the end of this module, you will have a solid foundation in firearm knowledge and safe handling practices.</p>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'firearm-overview',
                'module_key' => 'module-1',
                'title' => 'Firearm Overview',
                'description' => 'Understanding firearm basics, safety concepts, and classification of weapons.',
                'sort_order' => 2,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '1.1 Firearm Overview',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <h2 class="text-4xl font-bold text-gray-900 mb-2">1.1 Firearm Overview</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-8 flex-grow">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-gun text-violet-500"></i>
                    What is a Firearm?
                </h3>
                <p class="text-lg text-gray-700 leading-relaxed">
                    A firearm is a device designed to propel projectiles through a barrel using the force generated by a controlled explosion. Modern firearms are precision instruments used in law enforcement, military, and civilian applications.
                </p>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-violet-500"></i>
                    General Safety Concept Overview
                </h3>
                <ul class="text-lg text-gray-700 space-y-3">
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span>Always treat every firearm as if it were loaded</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span>Keep the muzzle pointed in a safe direction</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span>Keep your finger off the trigger until ready</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span>Be aware of your target and surroundings</span></li>
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-layer-group text-violet-500"></i>
                    Firearm Classification Basics
                </h3>
                <ul class="text-lg text-gray-700 space-y-3">
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span><strong>Pistols:</strong> Handheld firearms designed for one or two-handed operation</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span><strong>Rifles:</strong> Long-barreled firearms designed for accuracy at distance</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span><strong>Shotguns:</strong> Large-caliber weapons designed for spread ammunition</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'weapon-types',
                'module_key' => 'module-1',
                'title' => 'Weapon Types in System',
                'description' => 'Overview of the different weapon types used in the marksmanship system.',
                'sort_order' => 3,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '1.2 Weapon Types in System',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <h2 class="text-4xl font-bold text-gray-900 mb-2">1.2 Weapon Types in System</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-10 flex-grow">
            <div class="border-l-4 border-violet-500 pl-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">• 9mm Pistol</h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Semi-automatic operation concept:</strong> Fires one round per trigger pull, automatically chambers the next round</span></li>
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Magazine-fed system:</strong> Ammunition stored in removable magazines, typically 15-17 rounds</span></li>
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Common use in law enforcement:</strong> Standard duty weapon for most police departments</span></li>
                </ul>
            </div>
            <div class="border-l-4 border-violet-500 pl-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">• .45 Caliber Pistol</h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Larger caliber impact concept:</strong> Larger projectile (11.43mm) delivers more stopping power</span></li>
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Semi-automatic mechanism:</strong> Same operational principle as 9mm but with enhanced power</span></li>
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Recoil difference vs 9mm:</strong> Noticeably higher recoil impulse requires experienced handling</span></li>
                </ul>
            </div>
            <div class="border-l-4 border-violet-500 pl-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">• .38 Revolver</h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Cylinder-based firing system:</strong> Ammunition held in rotating cylinder with typically 5-6 chambers</span></li>
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Revolving mechanism:</strong> Cylinder rotates with each trigger pull to align next round</span></li>
                    <li class="flex gap-3"><i class="fas fa-arrow-right text-violet-500 mt-1"></i><span><strong>Simplicity and reliability:</strong> Fewer moving parts make it extremely dependable</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'parts-identification',
                'module_key' => 'module-1',
                'title' => 'Parts Identification',
                'description' => 'Identifying key components of pistols, revolvers, and ammunition systems.',
                'sort_order' => 4,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '1.3 Parts Identification',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <h2 class="text-4xl font-bold text-gray-900 mb-2">1.3 Parts Identification</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-10 flex-grow">
            <div class="bg-violet-50 rounded-lg p-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-crosshairs text-violet-500"></i>Pistol Components</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p class="text-gray-700"><strong class="text-violet-600">Slide:</strong> The moving top portion that cycles back and forth</p>
                        <p class="text-gray-700"><strong class="text-violet-600">Barrel:</strong> Rifled tube through which projectile travels</p>
                        <p class="text-gray-700"><strong class="text-violet-600">Frame:</strong> Lower structural component holding internal mechanisms</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-700"><strong class="text-violet-600">Trigger:</strong> Lever that releases the firing mechanism</p>
                        <p class="text-gray-700"><strong class="text-violet-600">Hammer:</strong> Component that strikes the firing pin</p>
                        <p class="text-gray-700"><strong class="text-violet-600">Sights:</strong> Aiming devices on top of the slide</p>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-sync-alt text-blue-500"></i>Revolver Components</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p class="text-gray-700"><strong class="text-blue-600">Cylinder:</strong> Rotating chamber holding ammunition</p>
                        <p class="text-gray-700"><strong class="text-blue-600">Hammer:</strong> Cocked and released to fire each round</p>
                        <p class="text-gray-700"><strong class="text-blue-600">Trigger:</strong> Controls hammer release and cylinder rotation</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-700"><strong class="text-blue-600">Frame:</strong> Main body structure</p>
                        <p class="text-gray-700"><strong class="text-blue-600">Barrel:</strong> Fixed component through which projectile exits</p>
                        <p class="text-gray-700"><strong class="text-blue-600">Crane:</strong> Mechanism that swings out to load cylinder</p>
                    </div>
                </div>
            </div>
            <div class="bg-amber-50 rounded-lg p-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-cubes text-amber-600"></i>Ammunition Systems</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="font-semibold text-gray-800 mb-2">Magazine System (Pistols)</p>
                        <ul class="space-y-2 text-gray-700">
                            <li>• Detachable magazines hold ammunition</li>
                            <li>• Spring-fed rounds move upward</li>
                            <li>• Quick magazine changes possible</li>
                            <li>• Typical capacity: 10-20+ rounds</li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 mb-2">Cylinder System (Revolvers)</p>
                        <ul class="space-y-2 text-gray-700">
                            <li>• Fixed cylinder with chambers</li>
                            <li>• Manual loading and unloading</li>
                            <li>• Cylinder rotates for alignment</li>
                            <li>• Typical capacity: 5-6 rounds</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'firearm-safety',
                'module_key' => 'module-1',
                'title' => 'Firearm Safety & Handling',
                'description' => 'Core safety rules and proper handling procedures for firearms.',
                'sort_order' => 5,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '1.4 Firearm Safety & Handling',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <h2 class="text-4xl font-bold text-gray-900 mb-2">1.4 Firearm Safety & Handling</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-8 flex-grow">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4"><i class="fas fa-shield-alt text-violet-500 mr-2"></i>Core Safety Rules</h3>
                <ul class="text-lg text-gray-700 space-y-3">
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span><strong>Rule 1:</strong> All guns are always loaded — treat them as such</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span><strong>Rule 2:</strong> Never let the muzzle cover anything you are not willing to destroy</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span><strong>Rule 3:</strong> Keep your finger off the trigger until your sights are on target</span></li>
                    <li class="flex gap-3"><span class="text-violet-500 font-bold">•</span><span><strong>Rule 4:</strong> Be sure of your target and what lies beyond it</span></li>
                </ul>
            </div>
            <div class="bg-amber-50 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3"><i class="fas fa-triangle-exclamation text-amber-600 mr-2"></i>Handling Procedures</h3>
                <p class="text-gray-700 leading-relaxed">Always engage the safety mechanism when handling. Keep the muzzle pointed downrange. Use proper grip and stance. Regular maintenance ensures safe operation and longevity of the firearm.</p>
            </div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'ammunition-caliber',
                'module_key' => 'module-1',
                'title' => 'Ammunition & Caliber',
                'description' => 'Understanding ammunition components and common caliber types.',
                'sort_order' => 6,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '1.5 Ammunition & Caliber Overview',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <h2 class="text-4xl font-bold text-gray-900 mb-2">1.5 Ammunition & Caliber Overview</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-8 flex-grow">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4"><i class="fas fa-cubes text-violet-500 mr-2"></i>Ammunition Components</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-700"><strong>Cartridge Case:</strong> Metal container holding all components together</p></div>
                    <div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-700"><strong>Primer:</strong> Ignition source that initiates the powder burn</p></div>
                    <div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-700"><strong>Propellant:</strong> Chemical powder that burns to create gas pressure</p></div>
                    <div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-700"><strong>Projectile:</strong> The bullet that exits the barrel toward the target</p></div>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4"><i class="fas fa-ruler text-violet-500 mr-2"></i>Common Calibers</h3>
                <ul class="text-lg text-gray-700 space-y-2">
                    <li><strong>9mm Parabellum:</strong> Standard NATO pistol caliber, widely used in law enforcement</li>
                    <li><strong>.45 ACP:</strong> Larger caliber with greater stopping power</li>
                    <li><strong>.38 Special:</strong> Common revolver caliber, moderate recoil</li>
                    <li><strong>5.56mm NATO:</strong> Standard rifle caliber for military applications</li>
                </ul>
            </div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],

            // ======================== MODULE 2 ========================
            [
                'lesson_key' => 'module-2-intro',
                'module_key' => 'module-2',
                'title' => 'Marksmanship & Firing Techniques',
                'description' => 'Module 2 introduction covering marksmanship fundamentals.',
                'sort_order' => 1,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => 'Module 2 Introduction',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-3xl w-full text-center">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 2</span>
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-3xl"><i class="fas fa-crosshairs"></i></div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Marksmanship &amp; Firing Techniques</h1>
        <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">This module focuses on the fundamentals of marksmanship including firing principles, proper stance and grip, and trigger control.</p>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'firing-principles',
                'module_key' => 'module-2',
                'title' => 'Firing Principles',
                'description' => 'Fundamental principles of accurate firing including sight alignment and trigger control.',
                'sort_order' => 2,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '2.1 Firing Principles',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 2</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">2.1 Firing Principles</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-8 flex-grow">
            <p class="text-lg text-gray-700 leading-relaxed">Understanding the fundamentals of firing is essential for accuracy and safety. The key principles include sight alignment, trigger control, breath control, and follow-through.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Sight Alignment</h4><p class="text-gray-600">Proper alignment of front and rear sights with the target</p></div>
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Trigger Control</h4><p class="text-gray-600">Smooth, steady squeeze without disturbing sight picture</p></div>
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Breath Control</h4><p class="text-gray-600">Timing your shot between breaths for stability</p></div>
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Follow-Through</h4><p class="text-gray-600">Maintaining form after the shot breaks</p></div>
            </div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'stance-grip',
                'module_key' => 'module-2',
                'title' => 'Stance & Grip',
                'description' => 'Proper shooting stances and grip techniques for accuracy.',
                'sort_order' => 3,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '2.2 Stance & Grip',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 2</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">2.2 Stance &amp; Grip</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-6">
            <p class="text-lg text-gray-700">Proper stance provides a stable platform for accurate shooting. The isosceles and Weaver stances are the most common.</p>
            <div class="bg-blue-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Isosceles Stance</h4><p class="text-gray-600">Feet shoulder-width apart, arms extended, body squared to the target. Provides natural pointing and good recoil management.</p></div>
            <div class="bg-blue-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Weaver Stance</h4><p class="text-gray-600">Strong foot back, support arm bent, pushing forward while pulling back with the strong hand. Offers better stability for precision shots.</p></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'frame-separation',
                'module_key' => 'module-2',
                'title' => 'Frame Separation',
                'description' => 'Understanding the process of safely separating the slide from the frame.',
                'sort_order' => 4,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '2.3 Frame Separation',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 2</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">2.3 Frame Separation</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-6">
            <p class="text-lg text-gray-700">Frame separation is a critical step in the disassembly process. Understanding how to safely separate the slide from the frame is essential for cleaning and maintenance.</p>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Safety First</h4><p class="text-gray-600">Always verify the weapon is clear: remove the magazine, lock the slide back, and inspect the chamber visually and physically.</p></div>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Slide Lock</h4><p class="text-gray-600">Engage the slide lock/release. Rotate the takedown lever downward to release the slide assembly from the frame rails.</p></div>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Separation</h4><p class="text-gray-600">Slide the entire slide assembly forward and off the frame. The recoil spring and guide rod will remain with the slide.</p></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'slide-removal',
                'module_key' => 'module-2',
                'title' => 'Slide Removal',
                'description' => 'Step-by-step process of removing the slide for detailed cleaning.',
                'sort_order' => 5,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '2.4 Slide Removal',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 2</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">2.4 Slide Removal</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-6">
            <p class="text-lg text-gray-700">After frame separation, the slide can be further disassembled for detailed cleaning and inspection of internal components.</p>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Compress the Recoil Spring</h4><p class="text-gray-600">Using firm pressure, compress the recoil spring and guide rod assembly to relieve tension.</p></div>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Remove the Guide Rod</h4><p class="text-gray-600">Lift the guide rod and spring assembly out of the slide. Set aside in a safe location.</p></div>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Remove the Barrel</h4><p class="text-gray-600">Lift the barrel from the slide by tilting the chamber end upward. The barrel should come free without force.</p></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'barrel-inspection',
                'module_key' => 'module-2',
                'title' => 'Barrel Inspection',
                'description' => 'How to properly inspect the barrel for wear, damage, and cleanliness.',
                'sort_order' => 6,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '2.5 Barrel Inspection',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 2</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">2.5 Barrel Inspection</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-6">
            <p class="text-lg text-gray-700">Regular barrel inspection is critical for safety and accuracy. Look for signs of wear, corrosion, or obstruction.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Visual Inspection</h4><p class="text-gray-600">Check the bore for cleanliness, pitting, and rifling wear. Shine a light through the barrel to examine the entire length.</p></div>
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Chamber Check</h4><p class="text-gray-600">Inspect the chamber for burrs, scratches, or carbon buildup that could impede feeding or extraction.</p></div>
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Muzzle Crown</h4><p class="text-gray-600">Examine the muzzle crown for nicks or damage. A damaged crown negatively affects accuracy.</p></div>
                <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2">Function Check</h4><p class="text-gray-600">After inspection and cleaning, verify the barrel locks up correctly with the slide and frame.</p></div>
            </div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],

            // ======================== MODULE 3 ========================
            [
                'lesson_key' => 'module-3-intro',
                'module_key' => 'module-3',
                'title' => 'Weapon Maintenance & Safety',
                'description' => 'Module 3 introduction covering weapon maintenance procedures and safety.',
                'sort_order' => 1,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => 'Module 3 Introduction',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-3xl w-full text-center">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 3</span>
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-3xl"><i class="fas fa-tools"></i></div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Weapon Maintenance &amp; Safety</h1>
        <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">This module covers proper weapon maintenance procedures, cleaning techniques, common malfunction identification, and safe storage and transportation practices.</p>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'cleaning',
                'module_key' => 'module-3',
                'title' => 'Cleaning',
                'description' => 'Proper cleaning procedures for firearm maintenance.',
                'sort_order' => 2,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '3.1 Cleaning',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 3</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">3.1 Cleaning</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <p class="text-lg text-gray-700 leading-relaxed mb-6">Proper maintenance extends the life of your firearm and ensures reliable operation. Regular cleaning, lubrication, and inspection are the three pillars of weapon care.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2"><i class="fas fa-broom text-violet-500 mr-1"></i> Cleaning</h4><p class="text-gray-600 text-sm">Remove carbon fouling, residue, and debris from the barrel and action after every use.</p></div>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2"><i class="fas fa-oil-can text-violet-500 mr-1"></i> Lubrication</h4><p class="text-gray-600 text-sm">Apply appropriate lubricant to moving parts to reduce friction and prevent wear.</p></div>
            <div class="bg-violet-50 rounded-lg p-5"><h4 class="font-bold text-gray-800 mb-2"><i class="fas fa-search text-violet-500 mr-1"></i> Inspection</h4><p class="text-gray-600 text-sm">Check for cracks, corrosion, and worn components before and after each use.</p></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'lubrication',
                'module_key' => 'module-3',
                'title' => 'Lubrication',
                'description' => 'Essential tools, solvents, and lubricants for firearm maintenance.',
                'sort_order' => 3,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '3.2 Lubrication',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 3</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">3.2 Lubrication</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><h3 class="text-xl font-semibold text-gray-800 mb-3">Essential Tools</h3><ul class="text-gray-700 space-y-2 text-lg"><li><i class="fas fa-check text-emerald-500 mr-2"></i>Cleaning rod with bore brush</li><li><i class="fas fa-check text-emerald-500 mr-2"></i>Patch holder and cleaning patches</li><li><i class="fas fa-check text-emerald-500 mr-2"></i>Nylon and brass brushes</li><li><i class="fas fa-check text-emerald-500 mr-2"></i>Microfiber cloths and cotton swabs</li></ul></div>
            <div><h3 class="text-xl font-semibold text-gray-800 mb-3">Solvents &amp; Lubricants</h3><ul class="text-gray-700 space-y-2 text-lg"><li><i class="fas fa-tint text-blue-500 mr-2"></i>Bore cleaner / solvent</li><li><i class="fas fa-tint text-blue-500 mr-2"></i>CLP (Clean, Lubricate, Protect)</li><li><i class="fas fa-tint text-blue-500 mr-2"></i>Grease for slide rails</li><li><i class="fas fa-tint text-blue-500 mr-2"></i>Rust preventative oil</li></ul></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'spring-replacement',
                'module_key' => 'module-3',
                'title' => 'Spring Replacement',
                'description' => 'Step-by-step guide for field stripping, cleaning, and spring replacement.',
                'sort_order' => 4,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '3.3 Spring Replacement',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 3</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">3.3 Spring Replacement</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-4">
            <div class="bg-amber-50 p-5 rounded-lg"><h4 class="font-bold text-gray-800 mb-2">Step 1: Clear the Weapon</h4><p class="text-gray-600">Remove the magazine, check the chamber, and verify the weapon is unloaded.</p></div>
            <div class="bg-amber-50 p-5 rounded-lg"><h4 class="font-bold text-gray-800 mb-2">Step 2: Field Strip</h4><p class="text-gray-600">Separate the slide from the frame (pistol) or remove the bolt group (rifle) for access to key components.</p></div>
            <div class="bg-amber-50 p-5 rounded-lg"><h4 class="font-bold text-gray-800 mb-2">Step 3: Clean &amp; Lubricate</h4><p class="text-gray-600">Run bore brush and patches through the barrel. Clean all components, then apply thin lubricant to contact surfaces.</p></div>
            <div class="bg-amber-50 p-5 rounded-lg"><h4 class="font-bold text-gray-800 mb-2">Step 4: Reassemble &amp; Function Check</h4><p class="text-gray-600">Reassemble the weapon, perform a safety and function check to ensure correct operation.</p></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'part-inspection',
                'module_key' => 'module-3',
                'title' => 'Part Inspection',
                'description' => 'Common firearm malfunctions and how to identify and resolve them.',
                'sort_order' => 5,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '3.4 Part Inspection',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 3</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">3.4 Part Inspection</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="space-y-4">
            <div class="bg-red-50 p-5 rounded-lg border-l-4 border-red-400"><h4 class="font-bold text-gray-800 mb-1">Failure to Feed (FTF)</h4><p class="text-gray-600 text-sm">The cartridge does not enter the chamber. Clear by locking the slide back, removing the magazine, and inspecting the chamber.</p></div>
            <div class="bg-red-50 p-5 rounded-lg border-l-4 border-red-400"><h4 class="font-bold text-gray-800 mb-1">Failure to Extract (FTE)</h4><p class="text-gray-600 text-sm">The spent casing is not ejected. Tap the magazine, rack the slide, and continue if clear.</p></div>
            <div class="bg-red-50 p-5 rounded-lg border-l-4 border-red-400"><h4 class="font-bold text-gray-800 mb-1">Stovepipe</h4><p class="text-gray-600 text-sm">A spent casing is caught partially ejected. Slap the magazine, rack the slide to clear the obstruction.</p></div>
            <div class="bg-red-50 p-5 rounded-lg border-l-4 border-red-400"><h4 class="font-bold text-gray-800 mb-1">Double Feed</h4><p class="text-gray-600 text-sm">Two cartridges attempt to feed simultaneously. Lock the slide, remove the magazine, rack multiple times to clear.</p></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
            [
                'lesson_key' => 'troubleshooting',
                'module_key' => 'module-3',
                'title' => 'Troubleshooting',
                'description' => 'Safe storage and transportation practices for firearms.',
                'sort_order' => 6,
                'pages' => [
                    [
                        'page_index' => 0,
                        'title' => '3.5 Troubleshooting',
                        'body_html' => <<<'HTML'
<div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
    <div class="max-w-4xl w-full text-left">
        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 3</span>
        <h2 class="text-4xl font-bold text-gray-900 mb-2">3.5 Troubleshooting</h2>
        <div class="h-1 w-24 bg-violet-500 mb-8"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><h3 class="text-xl font-semibold text-gray-800 mb-3"><i class="fas fa-box text-violet-500 mr-2"></i>Storage</h3><ul class="text-gray-700 space-y-2"><li>Use a locked gun safe or cabinet</li><li>Store ammunition separately from firearms</li><li>Use trigger locks or cable locks</li><li>Control humidity with dehumidifiers</li></ul></div>
            <div><h3 class="text-xl font-semibold text-gray-800 mb-3"><i class="fas fa-truck text-violet-500 mr-2"></i>Transportation</h3><ul class="text-gray-700 space-y-2"><li>Unload the firearm before transport</li><li>Use a locked, hard-sided case</li><li>Know federal, state, and local transport laws</li><li>Declare firearms when flying (checked baggage only)</li></ul></div>
        </div>
    </div>
</div>
HTML
                    ],
                ],
            ],
        ];

        foreach ($lessons as $data) {
            $pages = $data['pages'];
            unset($data['pages']);

            $lessonData = [
                'key' => $data['lesson_key'],
                'module_key' => $data['module_key'],
                'title' => $data['title'],
                'description' => $data['description'],
                'sort_order' => $data['sort_order'],
            ];

            $lesson = Lesson::firstOrCreate(
                ['key' => $lessonData['key']],
                $lessonData
            );

            foreach ($pages as $pageData) {
                LessonPage::firstOrCreate(
                    [
                        'lesson_id' => $lesson->id,
                        'page_index' => $pageData['page_index'],
                    ],
                    [
                        'lesson_index' => 0,
                        'title' => $pageData['title'],
                        'body_html' => $pageData['body_html'],
                    ]
                );
            }
        }
    }
}
