<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // Module 1: Gun Parts (20 questions)
        $m1 = [
            ['q' => 'What is a firearm?', 'o' => ['A device that propels projectiles using controlled explosion', 'A hand tool used for cutting metal', 'A type of engine mechanism', 'A safety device used in construction'], 'c' => 0],
            ['q' => 'What is the first and most important rule of firearm safety?', 'o' => ['Keep your finger off the trigger', 'Always treat every firearm as if it were loaded', 'Never point the muzzle at anything you don\'t want to destroy', 'Be aware of your target and what lies beyond'], 'c' => 1],
            ['q' => 'Which firearm classification is designed for one or two-handed operation?', 'o' => ['Rifle', 'Shotgun', 'Pistol', 'Machine Gun'], 'c' => 2],
            ['q' => 'Which component of the firearm houses the trigger group and magazine well?', 'o' => ['Slide', 'Barrel', 'Frame / Lower Receiver', 'Magazine'], 'c' => 2],
            ['q' => 'What caliber is considered the standard NATO pistol round?', 'o' => ['.45 ACP', '9mm Parabellum', '.38 Special', '5.56mm NATO'], 'c' => 1],
            ['q' => 'What is the purpose of the primer in ammunition?', 'o' => ['To hold the bullet in place', 'To ignite the propellant powder', 'To reduce recoil', 'To stabilize the bullet in flight'], 'c' => 1],
            ['q' => 'Which weapon type uses a rotating cylinder to hold ammunition?', 'o' => ['Semi-automatic pistol', 'Revolver', 'Bolt-action rifle', 'Lever-action shotgun'], 'c' => 1],
            ['q' => 'What is the purpose of the firing pin in a firearm?', 'o' => ['To push the bullet into the chamber', 'To strike the primer and ignite the cartridge', 'To hold the slide in place', 'To eject spent casings'], 'c' => 1],
            ['q' => 'What does the term "semi-automatic" mean?', 'o' => ['The firearm fires continuously while the trigger is held', 'One round fires per trigger pull, next round is automatically chambered', 'The firearm requires manual cocking before each shot', 'The barrel automatically adjusts for accuracy'], 'c' => 1],
            ['q' => 'What are the four main components of a cartridge?', 'o' => ['Barrel, trigger, sight, stock', 'Case, primer, propellant, projectile', 'Slide, frame, hammer, magazine', 'Cylinder, crane, grip, muzzle'], 'c' => 1],
            ['q' => 'What is the typical magazine capacity of a standard 9mm pistol?', 'o' => ['5-6 rounds', '10-12 rounds', '15-17 rounds', '30-40 rounds'], 'c' => 2],
            ['q' => 'Which part of the firearm is a rifled tube through which the projectile travels?', 'o' => ['Slide', 'Barrel', 'Frame', 'Magazine'], 'c' => 1],
            ['q' => 'Which of the following is NOT one of the four fundamental firearm safety rules?', 'o' => ['Keep your finger off the trigger until ready to shoot', 'Always clean your firearm after every use', 'Never let the muzzle cover anything you are not willing to destroy', 'Be sure of your target and what lies beyond it'], 'c' => 1],
            ['q' => 'What does a firearm\'s "caliber" refer to?', 'o' => ['The length of the barrel', 'The internal diameter of the barrel', 'The weight of the firearm', 'The magazine capacity'], 'c' => 1],
            ['q' => 'Which safety rule emphasizes checking what is beyond your target?', 'o' => ['Rule 1: All guns are always loaded', 'Rule 2: Never let the muzzle cover...', 'Rule 3: Keep your finger off the trigger...', 'Rule 4: Be sure of your target and what lies beyond it'], 'c' => 3],
            ['q' => 'What is the role of the extractor in a firearm?', 'o' => ['To push rounds from the magazine into the chamber', 'To remove the spent casing from the chamber', 'To hold the barrel in place', 'To aim the firearm at the target'], 'c' => 1],
            ['q' => 'What is the main difference between a semi-automatic pistol and a revolver?', 'o' => ['Revolvers are more accurate than pistols', 'Pistols use a detachable magazine; revolvers use a rotating cylinder', 'Pistols have longer barrels than revolvers', 'Revolvers are semi-automatic; pistols are not'], 'c' => 1],
            ['q' => 'What should you always do immediately when picking up a firearm?', 'o' => ['Load it with ammunition', 'Check if it is loaded and clear the chamber', 'Point it at the target immediately', 'Clean the barrel thoroughly'], 'c' => 1],
            ['q' => 'A .45 caliber pistol has what advantage over a 9mm pistol?', 'o' => ['Higher magazine capacity', 'Lower recoil impulse', 'Greater stopping power', 'Lighter weight'], 'c' => 2],
            ['q' => 'Which component of a revolver swings out to allow loading?', 'o' => ['The hammer', 'The trigger guard', 'The crane', 'The barrel'], 'c' => 2],
        ];

        // Module 2: Marksmanship & Firing Techniques (20 questions)
        $m2 = [
            ['q' => 'Which stance involves both arms extended with the body squared to the target?', 'o' => ['Weaver Stance', 'Isosceles Stance', 'Bladed Stance', 'Crouched Stance'], 'c' => 1],
            ['q' => 'What is the primary purpose of breath control in marksmanship?', 'o' => ['To reduce heart rate', 'To minimize body movement during the shot', 'To increase oxygen to the eyes', 'To relax the trigger finger'], 'c' => 1],
            ['q' => 'What does sight alignment refer to?', 'o' => ['Aligning the barrel with the target', 'Proper alignment of front and rear sights with the target', 'Positioning the body parallel to the target', 'Adjusting the trigger pull weight'], 'c' => 1],
            ['q' => 'In the Weaver stance, where is the strong foot positioned?', 'o' => ['Even with the support foot', 'Forward of the support foot', 'Back behind the support foot', 'At a 45-degree angle to the target'], 'c' => 2],
            ['q' => 'What is trigger control?', 'o' => ['Pulling the trigger as fast as possible', 'Smooth, steady squeeze without disturbing sight picture', 'Using the index finger to pull the trigger sideways', 'Adjusting the trigger position for comfort'], 'c' => 1],
            ['q' => 'Which principle refers to maintaining form after the shot breaks?', 'o' => ['Sight Alignment', 'Trigger Control', 'Breath Control', 'Follow-Through'], 'c' => 3],
            ['q' => 'Which stance provides better stability for precision shots?', 'o' => ['Isosceles Stance', 'Weaver Stance', 'Crouching Stance', 'One-Handed Stance'], 'c' => 1],
            ['q' => 'What is the first step in clearing a semi-automatic pistol for disassembly?', 'o' => ['Remove the barrel', 'Remove the magazine and check the chamber', 'Dry fire the weapon', 'Remove the grips'], 'c' => 1],
            ['q' => 'What is the correct sequence for assembling a Glock 9mm?', 'o' => ['Barrel, Guide Rod, Slide, Frame', 'Frame, Guide Rod, Barrel, Slide', 'Slide, Barrel, Guide Rod, Frame', 'Barrel, Slide, Frame, Guide Rod'], 'c' => 1],
            ['q' => 'What is the purpose of the guide rod in a Glock pistol?', 'o' => ['To hold the barrel in place', 'To guide the recoil spring and control slide movement', 'To aim the firearm', 'To eject spent casings'], 'c' => 1],
            ['q' => 'What is recoil management?', 'o' => ['The ability to absorb and control the rearward force of the firearm after firing', 'The speed of reloading the firearm', 'The process of cleaning the firearm after use', 'The technique of aiming at moving targets'], 'c' => 0],
            ['q' => 'In the Isosceles stance, the feet should be positioned how?', 'o' => ['One foot far behind the other', 'Shoulder-width apart, squared to the target', 'Together for stability', 'Crossed for better balance'], 'c' => 1],
            ['q' => 'What should your trigger finger be doing when not ready to fire?', 'o' => ['Resting on the trigger guard', 'Placed on the trigger', 'Pointing straight along the frame above the trigger guard', 'Wrapped around the grip'], 'c' => 2],
            ['q' => 'What happens when you perform a function check after reassembly?', 'o' => ['You test the firearm by firing it', 'You verify the firearm operates correctly without ammunition', 'You clean the firearm thoroughly', 'You adjust the sights for accuracy'], 'c' => 1],
            ['q' => 'Which marksmanship principle involves proper positioning of the body for stability?', 'o' => ['Sight Alignment', 'Stance and Grip', 'Trigger Control', 'Breath Control'], 'c' => 1],
            ['q' => 'When disassembling a Glock pistol, which part is removed first?', 'o' => ['The barrel', 'The slide', 'The magazine (if present)', 'The guide rod'], 'c' => 2],
            ['q' => 'What is the advantage of the Isosceles stance?', 'o' => ['Better stability for precision shots', 'Natural pointing and good recoil management', 'Lower profile for cover', 'Faster movement capability'], 'c' => 1],
            ['q' => 'How should you grip the firearm for maximum control?', 'o' => ['Loose grip with fingers relaxed', 'Firm, high grip with both hands, thumbs forward', 'One-handed grip with the support hand on the slide', 'Cross-wrist grip with arms twisted'], 'c' => 1],
            ['q' => 'What happens to the slide during the firing cycle of a semi-automatic pistol?', 'o' => ['It remains stationary', 'It moves rearward and then forward to chamber the next round', 'It rotates to align the next cartridge', 'It detaches from the frame'], 'c' => 1],
            ['q' => 'Why must you wear eye and ear protection during the assembly trainer simulation?', 'o' => ['To comply with standard safety protocols and simulate real range conditions', 'To improve visibility of small parts', 'To prevent the simulation from crashing', 'To communicate better with other trainees'], 'c' => 0],
        ];

        foreach ($m1 as $i => $q) {
            Activity::create([
                'module' => 1,
                'question_number' => $i + 1,
                'question_text' => $q['q'],
                'options' => $q['o'],
                'correct_answer' => $q['c'],
            ]);
        }

        foreach ($m2 as $i => $q) {
            Activity::create([
                'module' => 2,
                'question_number' => $i + 1,
                'question_text' => $q['q'],
                'options' => $q['o'],
                'correct_answer' => $q['c'],
            ]);
        }

        // Module 3: Maintenance (10 sample questions)
        $m3 = [
            ['q' => 'What is the first step in cleaning a firearm?', 'o' => ['Apply lubricant', 'Ensure the firearm is unloaded', 'Remove the barrel', 'Wipe down the exterior'], 'c' => 1],
            ['q' => 'What type of tool is used to clean the bore of a barrel?', 'o' => ['Wire brush', 'Cleaning rod with bore brush', 'Microfiber cloth', 'Cotton swab'], 'c' => 1],
            ['q' => 'What does CLP stand for?', 'o' => ['Clean, Load, Protect', 'Clean, Lubricate, Protect', 'Calibrate, Lubricate, Polish', 'Clean, Level, Prime'], 'c' => 1],
            ['q' => 'How often should a firearm be cleaned?', 'o' => ['Once a year', 'After every use', 'Only when malfunctioning', 'Every month regardless of use'], 'c' => 1],
            ['q' => 'What is a "failure to feed" (FTF)?', 'o' => ['The firearm fails to eject the spent casing', 'The cartridge does not enter the chamber', 'The trigger fails to reset', 'The safety fails to engage'], 'c' => 1],
            ['q' => 'What is a "stovepipe" malfunction?', 'o' => ['The barrel overheats', 'A spent casing is caught partially ejected', 'The magazine falls out', 'The slide locks back prematurely'], 'c' => 1],
            ['q' => 'How should firearms be stored when not in use?', 'o' => ['In a closet with ammunition', 'In a locked gun safe, separate from ammunition', 'Under a bed for quick access', 'On a wall mount in plain sight'], 'c' => 1],
            ['q' => 'What should you check during a barrel inspection?', 'o' => ['Barrel color', 'The bore for cleanliness, pitting, and rifling wear', 'Barrel weight', 'The barrel finish'], 'c' => 1],
            ['q' => 'Why is proper lubrication important?', 'o' => ['It makes the firearm look better', 'It reduces friction and prevents wear on moving parts', 'It increases the firearm\'s value', 'It makes the firearm quieter'], 'c' => 1],
            ['q' => 'What should you do if you encounter a double feed malfunction?', 'o' => ['Immediately fire the weapon', 'Lock the slide, remove the magazine, rack multiple times to clear', 'Tap the bottom of the magazine', 'Field strip and clean the firearm'], 'c' => 1],
        ];

        foreach ($m3 as $i => $q) {
            Activity::create([
                'module' => 3,
                'question_number' => $i + 1,
                'question_text' => $q['q'],
                'options' => $q['o'],
                'correct_answer' => $q['c'],
            ]);
        }
    }
}
