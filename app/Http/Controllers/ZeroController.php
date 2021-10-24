<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZeroController extends Controller
{
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
		// $this->astar($array, $start, $end);
	}
	
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
	
	public function astar($array, $start, $end) {
		dump("Start: ({$start[0]},{$start[1]}), End: ({$end[0]},{$end[1]}). Astar Start.");
		$row_num = [-1, 0, 0, 1];
		$col_num = [0, -1, 1, 0];
		
		$open_set[] = [
			'coordinates' => [$start[0],$start[1]],
			'priority' => 0
		];
		
		$closed_set[$start[0]][$start[1]] = true;
		$closed_set_display = ["({$start[0]},{$start[1]})"];
		$h_func = function(){
			return 1;
		};
		
		$g_scores = [];
		foreach($array as $ii => $row) {
			foreach($row as $jj => $col) {
				if($col == 1) continue;
				$g_scores["{$ii}_{$jj}"] = $ii+$jj;
			}
		}
		
		die;
		while(!empty($open_set)) {
			$nodes = array_column($open_set, 'priority');
			$current_node = $open_set[array_search(min($nodes), $nodes)];
		
			$curr_coord = $current_node['coordinates'];
			$closed_set_display[] = "({$curr_coord[0]},{$curr_coord[1]})";
			
			if($curr_coord[0] == $end[0] && $curr_coord[1] == $end[1]) {
				dump("Destination found. ");
				dump($nodes_visited);
				return;
			}
			
			$curr_node_key = array_search($current_node['coordinates'], array_column($open_set, 'coordinates'));
			unset($open_set[$curr_node_key]);
			$closed_set[$curr_coord[0]][$curr_coord[1]] = true;
			
			
			for ($i = 0; $i < 4; $i++) {
				$row = $curr_coord[0] + $row_num[$i];
				$col = $curr_coord[1] + $col_num[$i];
				
						
				if((isset($array[$row][$col]) && $array[$row][$col] != 1) && !isset($visited[$row][$col])) {
					$visited[$row][$col] = true;
					
					$ix_score = "{$curr_coord[0]}_{$curr_coord[1]}";
					$g_score = $g_scores[$ix_score] /*+ d_calc*/;
					$f_score = $g_score + $h_func[$ix_score];
					$to_queue = [
						'coordinates' => [$row,$col],
						'priority' => $f_score
					];
					
					$queue[] = $to_queue;
				}
			}
			dd($queue);
			
		}
		dump('No Possible Paths');
		return;
	}
}
