<?php
/**
 * Copyright (C) 2006-2012 University of the Philippines Linux Users' Group
 *
 * This file is part of Halalan.
 *
 * Halalan is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Halalan is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Halalan.  If not, see <http://www.gnu.org/licenses/>.
 */

class Turnout extends CI_Controller {

	var $admin;
	var $settings;

	function __construct()
	{
		parent::__construct();
		$this->admin = $this->session->userdata('admin');
		if ( ! $this->admin)
		{
			$this->session->set_flashdata('messages', array('negative', e('common_unauthorized')));
			redirect('gate/admin');
		}
		$this->settings = $this->config->item('halalan');
	}
	
	function index()
	{
		$election_id = get_cookie('selected_election');
		$uadmin = $this->session->userdata('admin');
		$u = isset($uadmin['electionid']) ? $uadmin['electionid'] : 1;
		
		if($u == 1) {
			$data['election_id'] = $election_id;
			$data['elections'] = $this->Election->select_all();
		}
		else {
			$data['election_id'] = $election_id;
			$data['elections'] = $this->Election->select_one($u);
		}
		
		// Get turnout statistics per block
		$data['turnout_data'] = $this->get_turnout_by_election($election_id);
		
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_turnout_title');
		$admin['body'] = $this->load->view('admin/turnout', $data, TRUE);
		$this->load->view('admin', $admin);
	}
	
	function get_turnout_by_election($election_id)
	{
		// Get all blocks for the selected election
		$blocks = $this->Block->select_all_by_election_id($election_id);
		
		$turnout_data = array();
		$total_voters = 0;
		$total_voted = 0;
		
		foreach ($blocks as $block) {
			$block_id = $block['id'];
			
			// Count total voters in this block
			$this->db->from('voters');
			$this->db->where('block_id', $block_id);
			$voters_count = $this->db->count_all_results();
			
			// Count voters who have voted in this block for this election
			$this->db->distinct();
			$this->db->select('voters.id');
			$this->db->from('voters');
			$this->db->join('voted', 'voters.id = voted.voter_id');
			$this->db->where('voters.block_id', $block_id);
			$this->db->where('voted.election_id', $election_id);
			$voted_count = $this->db->count_all_results();
			
			// Calculate percentage
			$percentage = $voters_count > 0 ? ($voted_count / $voters_count) * 100 : 0;
			
			$turnout_data[] = array(
				'block_name' => $block['block'],
				'total_voters' => $voters_count,
				'voted' => $voted_count,
				'percentage' => $percentage
			);
			
			$total_voters += $voters_count;
			$total_voted += $voted_count;
		}
		
		// Add aggregate row
		$aggregate_percentage = $total_voters > 0 ? ($total_voted / $total_voters) * 100 : 0;
		$data['blocks'] = $turnout_data;
		$data['aggregate'] = array(
			'total_voters' => $total_voters,
			'voted' => $total_voted,
			'percentage' => $aggregate_percentage
		);
		
		return $data;
	}

}

/* End of file turnout.php */
/* Location: ./application/controllers/admin/turnout.php */
