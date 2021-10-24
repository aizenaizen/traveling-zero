<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatrixController extends Controller
{
    public function index($algorithm, $dimension = 5, $start = null, $end = null, $wall_probability = 25) {
		$array = [];
		$point_one = $start == null ? [0,0] : explode(',', $start);
		$point_two = $end == null ? [$dimension-1, $dimension-1] : explode(',', $end);
		for($row = 0; $row < $dimension; $row++){
			for($col = 0; $col < $dimension; $col++){
				if(($row == $point_one[0] && $col == $point_one[1]) || ($row == $point_two[0] && $col == $point_two[1])) {
					$zero_one = 0;
				} else {
					$zero_one = rand(0, 100) < $wall_probability ? 1 : 0;
				}
				$array[$row][$col] = $zero_one;
			}
		}
		
		// $point_one = [0,1];
		// $point_two = [3,4];
		// $array = [
			// [0,0,0,1,0],
			// [0,1,1,0,1],
			// [0,0,0,1,1],
			// [1,1,0,0,0],
			// [0,0,0,0,1]
		// ];
		// $point_one = [0,1];
		// $point_two = [4,4];
		// $array = [
			// [0,0,0,0,0],
			// [0,0,0,0,0],
			// [0,0,0,0,0],
			// [0,0,0,0,0],
			// [0,0,0,0,0]
		// ];
		
		return view('display', compact('array', 'point_one', 'point_two', 'algorithm'));
	}
	
}
