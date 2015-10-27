<?php

namespace App\Rating;

class RatingType extends SplEnum {
	const __default = self::Unknown;
	const Unknown = -1;
	const Friendliness = 'friendliness';
	const Skill = 'skill';
	const Teamwork = 'teamwork';
	const FunFactor = 'funfactor';
}