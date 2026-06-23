<div class="presentation-shell">
    <div class="bg-gradient-to-r from-violet-950 to-violet-800 text-white px-6 sm:px-8 py-5 flex items-center justify-between gap-4 flex-wrap">
        <div>
            <p class="presentation-kicker text-violet-200 text-xs">Presentation</p>
            <h2 class="presentation-title text-3xl sm:text-4xl text-white mt-1">{{ $lesson?->title ?? 'Marksmanship Subject Overview' }}</h2>
        </div>
        <div class="text-xs text-violet-100 max-w-md">
            Use the arrows to move page by page. Scrolling is disabled in this shell.
        </div>
    </div>

    <div class="presentation-stage" id="presentation-stage">

        <!-- ==================== MODULE 1 ==================== -->

        <!-- Page 0: Introduction -->
        <section class="presentation-page active" data-page="0" data-lesson="intro">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 1: Module 1 Introduction -->
        <section class="presentation-page" data-page="1" data-lesson="1.0">
            <div class="presentation-content">
                <div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-3xl w-full text-center">
                        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 1</span>
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-3xl"><i class="fas fa-gun"></i></div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">Introduction to Firearms</h1>
                        <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">This module covers the fundamental concepts of firearms including weapon types, parts identification, safety protocols, and ammunition basics. By the end of this module, you will have a solid foundation in firearm knowledge and safe handling practices.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page 2: Lesson 1.1 Firearm Overview -->
        <section class="presentation-page" data-page="2" data-lesson="1.1">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 3: Lesson 1.2 Weapon Types in System -->
        <section class="presentation-page" data-page="3" data-lesson="1.2">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 4: Lesson 1.3 Parts Identification -->
        <section class="presentation-page" data-page="4" data-lesson="1.3">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 5: Lesson 1.4 Firearm Safety & Handling -->
        <section class="presentation-page" data-page="5" data-lesson="1.4">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 6: Lesson 1.5 Ammunition & Caliber Overview -->
        <section class="presentation-page" data-page="6" data-lesson="1.5">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Pages 7-26: Module 1 Assessment Q1-Q20 -->
        @php
            $m1Questions = [
                ['q' => 'What is a firearm?', 'o' => ['A device that propels projectiles using controlled explosion', 'A hand tool used for cutting metal', 'A type of engine mechanism', 'A safety device used in construction']],
                ['q' => 'What is the first and most important rule of firearm safety?', 'o' => ['Keep your finger off the trigger', 'Always treat every firearm as if it were loaded', 'Never point the muzzle at anything you don\'t want to destroy', 'Be aware of your target and what lies beyond']],
                ['q' => 'Which firearm classification is designed for one or two-handed operation?', 'o' => ['Rifle', 'Shotgun', 'Pistol', 'Machine Gun']],
                ['q' => 'Which component of the firearm houses the trigger group and magazine well?', 'o' => ['Slide', 'Barrel', 'Frame / Lower Receiver', 'Magazine']],
                ['q' => 'What caliber is considered the standard NATO pistol round?', 'o' => ['.45 ACP', '9mm Parabellum', '.38 Special', '5.56mm NATO']],
                ['q' => 'What is the purpose of the primer in ammunition?', 'o' => ['To hold the bullet in place', 'To ignite the propellant powder', 'To reduce recoil', 'To stabilize the bullet in flight']],
                ['q' => 'Which weapon type uses a rotating cylinder to hold ammunition?', 'o' => ['Semi-automatic pistol', 'Revolver', 'Bolt-action rifle', 'Lever-action shotgun']],
                ['q' => 'What is the purpose of the firing pin in a firearm?', 'o' => ['To push the bullet into the chamber', 'To strike the primer and ignite the cartridge', 'To hold the slide in place', 'To eject spent casings']],
                ['q' => 'What does the term "semi-automatic" mean?', 'o' => ['The firearm fires continuously while the trigger is held', 'One round fires per trigger pull, next round is automatically chambered', 'The firearm requires manual cocking before each shot', 'The barrel automatically adjusts for accuracy']],
                ['q' => 'What are the four main components of a cartridge?', 'o' => ['Barrel, trigger, sight, stock', 'Case, primer, propellant, projectile', 'Slide, frame, hammer, magazine', 'Cylinder, crane, grip, muzzle']],
                ['q' => 'What is the typical magazine capacity of a standard 9mm pistol?', 'o' => ['5-6 rounds', '10-12 rounds', '15-17 rounds', '30-40 rounds']],
                ['q' => 'Which part of the firearm is a rifled tube through which the projectile travels?', 'o' => ['Slide', 'Barrel', 'Frame', 'Magazine']],
                ['q' => 'Which of the following is NOT one of the four fundamental firearm safety rules?', 'o' => ['Keep your finger off the trigger until ready to shoot', 'Always clean your firearm after every use', 'Never let the muzzle cover anything you are not willing to destroy', 'Be sure of your target and what lies beyond it']],
                ['q' => 'What does a firearm\'s "caliber" refer to?', 'o' => ['The length of the barrel', 'The internal diameter of the barrel', 'The weight of the firearm', 'The magazine capacity']],
                ['q' => 'Which safety rule emphasizes checking what is beyond your target?', 'o' => ['Rule 1: All guns are always loaded', 'Rule 2: Never let the muzzle cover...', 'Rule 3: Keep your finger off the trigger...', 'Rule 4: Be sure of your target and what lies beyond it']],
                ['q' => 'What is the role of the extractor in a firearm?', 'o' => ['To push rounds from the magazine into the chamber', 'To remove the spent casing from the chamber', 'To hold the barrel in place', 'To aim the firearm at the target']],
                ['q' => 'What is the main difference between a semi-automatic pistol and a revolver?', 'o' => ['Revolvers are more accurate than pistols', 'Pistols use a detachable magazine; revolvers use a rotating cylinder', 'Pistols have longer barrels than revolvers', 'Revolvers are semi-automatic; pistols are not']],
                ['q' => 'What should you always do immediately when picking up a firearm?', 'o' => ['Load it with ammunition', 'Check if it is loaded and clear the chamber', 'Point it at the target immediately', 'Clean the barrel thoroughly']],
                ['q' => 'A .45 caliber pistol has what advantage over a 9mm pistol?', 'o' => ['Higher magazine capacity', 'Lower recoil impulse', 'Greater stopping power', 'Lighter weight']],
                ['q' => 'Which component of a revolver swings out to allow loading?', 'o' => ['The hammer', 'The trigger guard', 'The crane', 'The barrel']],
            ];
        @endphp
        @foreach ($m1Questions as $i => $qdata)
        <section class="presentation-page" data-page="{{ 7 + $i }}" data-lesson="1.6">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-2xl w-full">
                        <div class="text-center mb-6"><span class="text-violet-600 text-sm font-semibold uppercase tracking-wide">Module 1 Assessment</span><p class="text-gray-400 text-xs mt-1">Question {{ $i + 1 }} of {{ count($m1Questions) }}</p></div>
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <p class="font-semibold text-gray-900 mb-4 text-lg">{{ $i + 1 }}. {{ $qdata['q'] }}</p>
                            <div class="space-y-3">
                                @foreach ($qdata['o'] as $j => $opt)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-violet-50 cursor-pointer"><input type="radio" name="m1q{{ $i + 1 }}" class="accent-violet-600"><span class="text-gray-700">{{ $opt }}</span></label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endforeach

        <!-- Page 27: Module 1 Score Result -->
        <section class="presentation-page" data-page="27" data-lesson="1.7">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-2xl w-full text-center">
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-emerald-100 flex items-center justify-center">
                            <span class="text-3xl font-bold text-emerald-600">95%</span>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-1">Module 1 Assessment Result</h2>
                        <p class="text-gray-500 mb-6">You answered 19 of 20 questions correctly.</p>

                        <div class="text-left space-y-3 mb-8">
                            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-xmark text-red-500 mt-1"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Question 13 — You answered: <span class="text-red-600">&ldquo;Always clean your firearm after every use&rdquo;</span></p>
                                        <p class="text-sm text-emerald-700 mt-1"><i class="fas fa-check mr-1"></i>Correct answer: <span class="font-medium">&ldquo;Be sure of your target and what lies beyond it&rdquo;</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-violet-50 border border-violet-100 rounded-xl p-5 text-left">
                            <h4 class="font-semibold text-gray-900 mb-3 text-sm"><i class="fas fa-list-check text-violet-600 mr-2"></i>All Questions Review</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1.5 text-sm text-gray-700">
                                @for ($k = 0; $k < 20; $k++)
                                <div class="flex gap-2">
                                    @if ($k === 12)
                                    <span class="text-red-500 font-bold shrink-0">✗</span><span class="text-red-600"><span class="line-through">Always clean...</span> → Be sure of target</span>
                                    @else
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span><span>Q{{ $k + 1 }}: {{ Str::limit($m1Questions[$k]['o'][0], 40) }}</span>
                                    @endif
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== MODULE 2 ==================== -->

        <!-- Page 28: Module 2 Introduction -->
        <section class="presentation-page" data-page="28" data-lesson="2.0">
            <div class="presentation-content">
                <div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-3xl w-full text-center">
                        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 2</span>
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-3xl"><i class="fas fa-crosshairs"></i></div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">Marksmanship &amp; Firing Techniques</h1>
                        <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">This module focuses on the fundamentals of marksmanship including firing principles, proper stance and grip, and trigger control.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page 29: Lesson 2.1 Firing Principles -->
        <section class="presentation-page" data-page="29" data-lesson="2.1">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 30: Lesson 2.2 Stance & Grip -->
        <section class="presentation-page" data-page="30" data-lesson="2.2">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 31: Lesson 2.3 Frame Separation -->
        <section class="presentation-page" data-page="31" data-lesson="2.3">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 32: Lesson 2.4 Slide Removal -->
        <section class="presentation-page" data-page="32" data-lesson="2.4">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 33: Lesson 2.5 Barrel Inspection -->
        <section class="presentation-page" data-page="33" data-lesson="2.5">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Pages 34-53: Module 2 Assessment Q1-Q20 -->
        @php
            $m2Questions = [
                ['q' => 'Which stance involves both arms extended with the body squared to the target?', 'o' => ['Weaver Stance', 'Isosceles Stance', 'Bladed Stance', 'Crouched Stance']],
                ['q' => 'What is the primary purpose of breath control in marksmanship?', 'o' => ['To reduce heart rate', 'To minimize body movement during the shot', 'To increase oxygen to the eyes', 'To relax the trigger finger']],
                ['q' => 'What does sight alignment refer to?', 'o' => ['Aligning the barrel with the target', 'Proper alignment of front and rear sights with the target', 'Positioning the body parallel to the target', 'Adjusting the trigger pull weight']],
                ['q' => 'In the Weaver stance, where is the strong foot positioned?', 'o' => ['Even with the support foot', 'Forward of the support foot', 'Back behind the support foot', 'At a 45-degree angle to the target']],
                ['q' => 'What is trigger control?', 'o' => ['Pulling the trigger as fast as possible', 'Smooth, steady squeeze without disturbing sight picture', 'Using the index finger to pull the trigger sideways', 'Adjusting the trigger position for comfort']],
                ['q' => 'Which principle refers to maintaining form after the shot breaks?', 'o' => ['Sight Alignment', 'Trigger Control', 'Breath Control', 'Follow-Through']],
                ['q' => 'Which stance provides better stability for precision shots?', 'o' => ['Isosceles Stance', 'Weaver Stance', 'Crouching Stance', 'One-Handed Stance']],
                ['q' => 'What is the first step in clearing a semi-automatic pistol for disassembly?', 'o' => ['Remove the barrel', 'Remove the magazine and check the chamber', 'Dry fire the weapon', 'Remove the grips']],
                ['q' => 'What is the correct sequence for assembling a Glock 9mm?', 'o' => ['Barrel, Guide Rod, Slide, Frame', 'Frame, Guide Rod, Barrel, Slide', 'Slide, Barrel, Guide Rod, Frame', 'Barrel, Slide, Frame, Guide Rod']],
                ['q' => 'What is the purpose of the guide rod in a Glock pistol?', 'o' => ['To hold the barrel in place', 'To guide the recoil spring and control slide movement', 'To aim the firearm', 'To eject spent casings']],
                ['q' => 'What is recoil management?', 'o' => ['The ability to absorb and control the rearward force of the firearm after firing', 'The speed of reloading the firearm', 'The process of cleaning the firearm after use', 'The technique of aiming at moving targets']],
                ['q' => 'In the Isosceles stance, the feet should be positioned how?', 'o' => ['One foot far behind the other', 'Shoulder-width apart, squared to the target', 'Together for stability', 'Crossed for better balance']],
                ['q' => 'What should your trigger finger be doing when not ready to fire?', 'o' => ['Resting on the trigger guard', 'Placed on the trigger', 'Pointing straight along the frame above the trigger guard', 'Wrapped around the grip']],
                ['q' => 'What happens when you perform a function check after reassembly?', 'o' => ['You test the firearm by firing it', 'You verify the firearm operates correctly without ammunition', 'You clean the firearm thoroughly', 'You adjust the sights for accuracy']],
                ['q' => 'Which marksmanship principle involves proper positioning of the body for stability?', 'o' => ['Sight Alignment', 'Stance and Grip', 'Trigger Control', 'Breath Control']],
                ['q' => 'When disassembling a Glock pistol, which part is removed first?', 'o' => ['The barrel', 'The slide', 'The magazine (if present)', 'The guide rod']],
                ['q' => 'What is the advantage of the Isosceles stance?', 'o' => ['Better stability for precision shots', 'Natural pointing and good recoil management', 'Lower profile for cover', 'Faster movement capability']],
                ['q' => 'How should you grip the firearm for maximum control?', 'o' => ['Loose grip with fingers relaxed', 'Firm, high grip with both hands, thumbs forward', 'One-handed grip with the support hand on the slide', 'Cross-wrist grip with arms twisted']],
                ['q' => 'What happens to the slide during the firing cycle of a semi-automatic pistol?', 'o' => ['It remains stationary', 'It moves rearward and then forward to chamber the next round', 'It rotates to align the next cartridge', 'It detaches from the frame']],
                ['q' => 'Why must you wear eye and ear protection during the assembly trainer simulation?', 'o' => ['To comply with standard safety protocols and simulate real range conditions', 'To improve visibility of small parts', 'To prevent the simulation from crashing', 'To communicate better with other trainees']],
            ];
        @endphp
        @foreach ($m2Questions as $i => $qdata)
        <section class="presentation-page" data-page="{{ 34 + $i }}" data-lesson="2.6">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-2xl w-full">
                        <div class="text-center mb-6"><span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Module 2 Assessment</span><p class="text-gray-400 text-xs mt-1">Question {{ $i + 1 }} of {{ count($m2Questions) }}</p></div>
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <p class="font-semibold text-gray-900 mb-4 text-lg">{{ $i + 1 }}. {{ $qdata['q'] }}</p>
                            <div class="space-y-3">
                                @foreach ($qdata['o'] as $j => $opt)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-blue-50 cursor-pointer"><input type="radio" name="m2q{{ $i + 1 }}" class="accent-blue-600"><span class="text-gray-700">{{ $opt }}</span></label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endforeach

        <!-- Page 54: Module 2 Score Result -->
        <section class="presentation-page" data-page="54" data-lesson="2.7">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-2xl w-full text-center">
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-emerald-100 flex items-center justify-center">
                            <span class="text-3xl font-bold text-emerald-600">80%</span>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-1">Module 2 Assessment Result</h2>
                        <p class="text-gray-500 mb-6">You answered 16 of 20 questions correctly.</p>

                        <div class="text-left space-y-3 mb-8">
                            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-xmark text-red-500 mt-1"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Question 4 — You answered: <span class="text-red-600">&ldquo;Forward of the support foot&rdquo;</span></p>
                                        <p class="text-sm text-emerald-700 mt-1"><i class="fas fa-check mr-1"></i>Correct answer: <span class="font-medium">&ldquo;Back behind the support foot&rdquo;</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-xmark text-red-500 mt-1"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Question 8 — You answered: <span class="text-red-600">&ldquo;Remove the barrel&rdquo;</span></p>
                                        <p class="text-sm text-emerald-700 mt-1"><i class="fas fa-check mr-1"></i>Correct answer: <span class="font-medium">&ldquo;Remove the magazine and check the chamber&rdquo;</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-xmark text-red-500 mt-1"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Question 15 — You answered: <span class="text-red-600">&ldquo;Sight Alignment&rdquo;</span></p>
                                        <p class="text-sm text-emerald-700 mt-1"><i class="fas fa-check mr-1"></i>Correct answer: <span class="font-medium">&ldquo;Stance and Grip&rdquo;</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-xmark text-red-500 mt-1"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Question 19 — You answered: <span class="text-red-600">&ldquo;It rotates to align the next cartridge&rdquo;</span></p>
                                        <p class="text-sm text-emerald-700 mt-1"><i class="fas fa-check mr-1"></i>Correct answer: <span class="font-medium">&ldquo;It moves rearward and then forward to chamber the next round&rdquo;</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-violet-50 border border-violet-100 rounded-xl p-5 text-left">
                            <h4 class="font-semibold text-gray-900 mb-3 text-sm"><i class="fas fa-list-check text-violet-600 mr-2"></i>All Questions Review</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1.5 text-sm text-gray-700">
                                @for ($k = 0; $k < 20; $k++)
                                <div class="flex gap-2">
                                    @if (in_array($k, [3, 7, 14, 18]))
                                    <span class="text-red-500 font-bold shrink-0">✗</span><span class="text-red-600">{{ Str::limit($m2Questions[$k]['o'][0], 32) }} → {{ Str::limit($m2Questions[$k]['o'][1], 32) }}</span>
                                    @else
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span><span>Q{{ $k + 1 }}: {{ Str::limit($m2Questions[$k]['o'][0], 40) }}</span>
                                    @endif
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page 55: Assembly Trainer Introduction -->
        <section class="presentation-page" data-page="55" data-lesson="2.8">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-2xl w-full text-center">
                        <p class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-1">Hands-on Practice</p>
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Assemble &amp; Disassemble Trainer</h2>
                        <div class="h-1 w-24 bg-violet-500 mx-auto mb-6"></div>
                        <p class="text-gray-600 mb-6">Drag each part from the tray onto the pistol to assemble it layer by layer, or switch to Disassemble to remove the parts back into the tray.</p>
                        <button type="button" class="presentation-btn inline-flex items-center gap-2" onclick="document.getElementById('presentation-next').click()">
                            Get Started <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page 56: Assembly Trainer Layout -->
        <section class="presentation-page" data-page="56" data-lesson="2.8">
            <div class="presentation-content p-4 sm:p-6 overflow-auto" style="overflow: auto !important;">
                @include('Students.partials.assembly-simulator')
            </div>
        </section>

        <!-- ==================== MODULE 3 ==================== -->

        <!-- Page 57: Module 3 Introduction -->
        <section class="presentation-page" data-page="57" data-lesson="3.0">
            <div class="presentation-content">
                <div class="flex flex-col items-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-3xl w-full text-center">
                        <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-3 block">Module 3</span>
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-3xl"><i class="fas fa-tools"></i></div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">Weapon Maintenance &amp; Safety</h1>
                        <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">This module covers proper weapon maintenance procedures, cleaning techniques, common malfunction identification, and safe storage and transportation practices.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page 58: Lesson 3.1 Cleaning -->
        <section class="presentation-page" data-page="58" data-lesson="3.1">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 59: Lesson 3.2 Lubrication -->
        <section class="presentation-page" data-page="59" data-lesson="3.2">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 60: Lesson 3.3 Spring Replacement -->
        <section class="presentation-page" data-page="60" data-lesson="3.3">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 61: Lesson 3.4 Part Inspection -->
        <section class="presentation-page" data-page="61" data-lesson="3.4">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 62: Lesson 3.5 Troubleshooting -->
        <section class="presentation-page" data-page="62" data-lesson="3.5">
            <div class="presentation-content">
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
            </div>
        </section>

        <!-- Page 63: Module 3 Assessment -->
        <section class="presentation-page" data-page="63" data-lesson="3.6">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-2xl w-full text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-violet-100 flex items-center justify-center text-3xl"><i class="fas fa-file-pen text-violet-600"></i></div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Module 3 Assessment</h2>
                        <p class="text-gray-500 mb-6">Complete this assessment to test your knowledge of weapon maintenance, cleaning procedures, and safety protocols.</p>
                        <div class="bg-violet-50 border border-violet-100 rounded-xl p-6 text-left">
                            <h4 class="font-semibold text-gray-900 mb-3">Assessment Overview</h4>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex gap-2"><i class="fas fa-file-lines text-violet-500 mt-1"></i><span>20 multiple-choice questions</span></li>
                                <li class="flex gap-2"><i class="fas fa-clock text-violet-500 mt-1"></i><span>No time limit</span></li>
                                <li class="flex gap-2"><i class="fas fa-star text-violet-500 mt-1"></i><span>80% passing score</span></li>
                                <li class="flex gap-2"><i class="fas fa-rotate text-violet-500 mt-1"></i><span>Unlimited retakes</span></li>
                            </ul>
                        </div>
                        <p class="text-sm text-gray-400 mt-6">Click <strong>Next</strong> to begin the assessment when ready.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page 64: Module 3 Score Result -->
        <section class="presentation-page" data-page="64" data-lesson="3.7">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-2xl w-full text-center">
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-violet-100 flex items-center justify-center">
                            <span class="text-3xl font-bold text-violet-600">—</span>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-1">Module 3 Assessment Result</h2>
                        <p class="text-gray-500 mb-6">Complete the assessment to see your score.</p>
                        <div class="bg-violet-50 border border-violet-100 rounded-xl p-6 text-center">
                            <i class="fas fa-lock text-violet-400 text-2xl mb-3"></i>
                            <p class="text-gray-600">This module is locked. Complete Module 2 to unlock this assessment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Firing Range Eligibility -->
        <section class="presentation-page" data-page="65" data-lesson="4.0">
            <div class="presentation-content">
                <div class="flex flex-col justify-center items-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-lg mx-auto text-center">
                        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-check-circle text-4xl text-emerald-600"></i>
                        </div>
                        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Ready for the Firing Range</h1>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            You have completed all modules. You are now eligible to proceed to the Firing Range marksmanship simulation.
                        </p>
                        <div class="bg-violet-50 border border-violet-100 rounded-xl p-5 mb-8 text-left">
                            <p class="text-sm font-semibold text-violet-900 mb-2"><i class="fas fa-gun text-violet-600 mr-2"></i>What's next?</p>
                            <ul class="text-sm text-violet-700 space-y-2">
                                <li class="flex gap-2"><i class="fas fa-arrow-right text-violet-400 mt-0.5"></i><span>Apply your firearm knowledge in a timed simulation</span></li>
                                <li class="flex gap-2"><i class="fas fa-arrow-right text-violet-400 mt-0.5"></i><span>Test your accuracy with different weapon types</span></li>
                                <li class="flex gap-2"><i class="fas fa-arrow-right text-violet-400 mt-0.5"></i><span>Earn scores and track your marksmanship progress</span></li>
                            </ul>
                        </div>
                        <p class="text-sm text-gray-500">Ask your instructor to grant you access to the Firing Range.</p>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div class="border-t border-violet-100 bg-white/90 px-6 sm:px-8 py-4">
        <div class="presentation-nav">
            <button type="button" id="presentation-prev" class="presentation-btn">
                <i class="fas fa-arrow-left text-sm"></i> Previous
            </button>
            <span class="presentation-page-counter" id="page-counter">1 / 1</span>
            <button type="button" id="presentation-next" class="presentation-btn">
                Next <i class="fas fa-arrow-right text-sm"></i>
            </button>
        </div>
    </div>


</div>

<style>

</style>

