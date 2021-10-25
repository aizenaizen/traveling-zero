<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZeroController extends Controller
{
	// DRAFT CODES
    public function path_finder() {
		$array = [
			[0,0,0,1,0],
			[0,1,1,0,1],
			[0,0,0,1,1],
			[1,1,0,0,0],
			[0,0,0,0,1]
		];
		dump('Matrix:');
		dump($array);
		$start = [0,1];
		$end = [3,4];
	 
		$this->dfs($array, $start, $end);
		echo '--------';
		$this->bfs($array, $start, $end);
	}
	// DRAFT CODES
	// DRAFT CODES
	public function dfs($array, $start, $end) {
		dump("Start: ({$start[0]},{$start[1]}), End: ({$end[0]},{$end[1]}). DFS Start");
		$directions = [[0,1], [0,-1], [1,0], [-1,0]];
		
		$paths_taken = ["({$start[0]},{$start[1]})"];
		
		$explored[$start[0]][$start[1]] = true;
		$explorer[] = [$start[0],$start[1]];
		
		while(!empty($explorer)) {
			$current_node = array_pop($explorer);
			$x = $current_node[0];
			$y = $current_node[1];
			
			if($x == $end[0] && $y == $end[1]) {
				dump("Destination reached. Paths Explored:");
				dump($paths_taken);
				return;
			}
			foreach($directions as $dir){
				$x += $dir[0];
				$y += $dir[1];
				
				if(
					!isset($array[$x][$y]) || 
					(isset($array[$x][$y]) && $array[$x][$y] == 1) ||
					$x >= count($array[0]) ||
					$y >= count($array)
				) continue;
				if(isset($explored[$x][$y])) continue;
				
				$explored[$x][$y] = true;
				$paths_taken[] = "({$x},{$y})";
				
				$explorer[] = [$x, $y];
			}
		}
		
		dump('No Possible Paths');
		return;
	}
	// DRAFT CODES
	// DRAFT CODES
	public function bfs($array, $start, $end) {
		dump("Start: ({$start[0]},{$start[1]}), End: ({$end[0]},{$end[1]}). BFS Start.");
		$row_num = [-1, 0, 0, 1];
		$col_num = [0, -1, 1, 0];
		
		if($array[$start[0]][$start[1]] == 1 || $array[$end[0]][$end[1]] == 1) {
			dump('0ne of target node is on a "1" cell');
			return;
		}
		
		$nodes_visited = ["({$start[0]},{$start[1]})"];
		$visited[$start[0]][$start[1]] = true;
		$queue[] = [
			'coordinates' => [$start[0],$start[1]],
			'distance' => 0
		];
		
		while(!empty($queue)) {
			$current_node = reset($queue);
			$curr_coord = $current_node['coordinates'];
			$curr_distn = $current_node['distance'];
			
			if($curr_coord[0] == $end[0] && $curr_coord[1] == $end[1]) {
				dump("Destination found. Shortest path at ".$curr_distn." steps. Nodes Visited:");
				dump($nodes_visited);
				return;
			}
			array_shift($queue);
			for ($i = 0; $i < 4; $i++) {
				$row = $curr_coord[0] + $row_num[$i];
				$col = $curr_coord[1] + $col_num[$i];
				
				if((isset($array[$row][$col]) && $array[$row][$col] != 1) && !isset($visited[$row][$col])) {
					$visited[$row][$col] = true;
					$nodes_visited[] = "({$curr_coord[0]},{$curr_coord[1]})";
					
					$to_queue = [
						'coordinates' => [$row,$col],
						'distance' => $curr_distn + 1
					];
					
					$queue[] = $to_queue;
				}
			}
		}
		dump('No Possible Paths');
		return;
	}
	// DRAFT CODES
}
