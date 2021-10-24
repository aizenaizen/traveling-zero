<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SolverController extends Controller
{	
	public function dfs(Request $request) {
		$array = json_decode($request->matrix);
		$start = json_decode($request->point_one);
		$end = json_decode($request->point_two);
		
		$data['algorithm'] = 'dfs';
		
		$directions = [[0,-1], [-1,0], [0,1], [1,0]];
		$stack[] = [$start[0],$start[1]];
		$ii = 0;
		while(!empty($stack)) {
			$current_node = array_pop($stack);
			
			$x = $current_node[0];
			$y = $current_node[1];
			$taken_nodes[] = "({$x},{$y})";
			
			$explored[$x][$y] = true;
			$explored_nodes[] = "({$x},{$y})";
			
			if($x == $end[0] && $y == $end[1]) {
				$data['explored_nodes'] = $explored_nodes;
				$data['path'] = true; 
				$data['message'] = "The maze is solvable"; 
				echo json_encode($data);
				exit;
			}
			
			foreach($directions as $i => $dir){
				$x_neigbhor = $x + $dir[0];
				$y_neigbhor = $y + $dir[1];
				
				if(!isset($array[$x_neigbhor][$y_neigbhor]) || (isset($array[$x_neigbhor][$y_neigbhor]) && $array[$x_neigbhor][$y_neigbhor] == 1) || isset($explored[$x_neigbhor][$y_neigbhor])) continue;	
				
				$stack[] = [$x_neigbhor, $y_neigbhor];
				// if($x_neigbhor == $end[0] && $y_neigbhor == $end[1]) break;
				
			}
		}

		$data['path'] = false; 
		$data['explored_nodes'] = $explored_nodes;
		$data['message'] = "No Possible Path"; 
		echo json_encode($data);
		return;
	}
	
	public function bfs(Request $request) {
		$array = json_decode($request->matrix);
		$start = json_decode($request->point_one);
		$end = json_decode($request->point_two);
		
		$row_num = [-1, 0, 0, 1];
		$col_num = [0, -1, 1, 0];
		
		$visited[$start[0]][$start[1]] = true;
		$nodes_visited = ["({$start[0]},{$start[1]})"];
		$path = ["({$start[0]},{$start[1]})"];
		$queue[] = [
			'coordinates' => [$start[0],$start[1]],
			'distance' => 0
		];
		
		while(!empty($queue)) {
			$current_node = array_shift($queue);
			$path[] = $current_node;
			$curr_coord = $current_node['coordinates'];
			// $curr_distn = $current_node['distance'];
			
			$nodes_visited[] = "({$curr_coord[0]},{$curr_coord[1]})";
			if($curr_coord[0] == $end[0] && $curr_coord[1] == $end[1]) {
				$data['algorithm'] = 'bfs';
				$data['explored_nodes'] = $nodes_visited;
				// $data['shortest_distance'] = $curr_distn; 
				$data['path'] = true; 
				$data['message'] = "The maze is solvable"; 
				
				echo json_encode($data);
				exit;
			}
			
			for ($i = 0; $i < 4; $i++) {
				$row = $curr_coord[0] + $row_num[$i];
				$col = $curr_coord[1] + $col_num[$i];
				
				if((isset($array[$row][$col]) && $array[$row][$col] != 1) && !isset($visited[$row][$col])) {
					$visited[$row][$col] = true;
					
					$to_queue = [
						'coordinates' => [$row,$col],
						// 'distance' => $curr_distn + 1
					];
					
					$queue[] = $to_queue;
				}
			}
		}		
		
		$data['algorithm'] = 'bfs';
		$data['explored_nodes'] = $nodes_visited;
		$data['no_path'] = true; 
		$data['message'] = "No Possible Path"; 
		echo json_encode($data);
		exit;
	}
}
