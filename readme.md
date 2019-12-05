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
