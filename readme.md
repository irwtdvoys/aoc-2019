# Advent of Code 2019

https://adventofcode.com/2019

For the 2019 tasks I intent to use a Bolt project with some of the core libraries so there is no setup each day.

My intention (time permitting) once a task is done is to integrate some tests to prove correctness.

## Notes

##### Day 01

Started a day late =/ was a nice simple first task, part 2 was achieved with some simple recursion.

##### Day 02

I feel my solution is a bit reliant of the expectation that each instruction takes up four memory locations.

##### Day 03

Used a hash map to avoid drawing the whole grid (of unknown size) and used bitwise of the path to mark visiting cells. Expected more paths in part 2 but instead had to add in distance calculations.

##### Day 04

Got stuck with part 2 after misunderstanding the new double rule and thinking consecutive doubles `1111` were fine.

##### Day 05

Made a few improvements to the Intcode class from day 02. Removed the reliance on instruction size and I feel it's a lot more extensible now. Once the improvements were done parts 1 and 2 were fairly simple additions.

##### Day 06

Used recursion to calculate the numbers of orbits, slightly hacky when calculating if a body contains another in it's orbit.

##### Day 07

Main issue for the day was having to add an interrupt/wait system to the intcode as PHP is single threaded.

##### Day 08

No real issues here, decided just to render the image in ascii of the cli rather than worrying about a bitmap library.

##### Day 09

Missed implementing mode 2 on instruction results, but the test failure codes pointed out where the issues were and part 2 just worked.

##### Day 10

Cut corners in part 1 by using gradient of vectors to easily group asteroids in the same line of sight. Had to be rewritten using trig for part 2 as it wasn't very easy to emulate the rotation of the laser.

##### Day 11

Had an issue with the intcode, when looping with interrupt mode on it wasn't possible to tell the difference between a pause and a halt.

##### Day 12

Was disappointed in the lack of LCM/GCF in the PHP standard libraries, will be adding these method to the Bolt\Maths library.

##### Day 13

Had to bump the max memory of the intcode VM but no other real issues.

##### Day 14

Completed the first part recursively however part two would have taken ages to calculate to upgraded the system to allow for specifying multiples (not one at a time). This allowed estimation of the end goal and completed in just a few iterations.

##### Day 15

Used an exploring algorithm by following th left wall while marking distances to each tile in the map. The same code solved part two by resetting the map and counter once the oxygen system had been found. The map was lucky in that the oxygen system was at the end of a deadend.

##### Day 16

Didn't see the correct solution to part two without a hint. The solution to part one was very much not scalable...

##### Day 17

Had to increase VM memory again. Solved part two by manually selecting the patterns for A/B/C couldn't see a simple way of calculating it.

##### Day 18

Yeah... WiP...

##### Day 19

Part one is pretty inefficient, calculation is done for every cell. Part two is much more efficient will calculate for only cells after the previous row and only until it finds the first match. It then checks for a diagonal match to signal beam is wide enough. 

