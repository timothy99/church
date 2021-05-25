<?php namespace App\Libraries;

use CodeIgniter\Model;
use Config\Database;

class Query
{
	/**
	 * @author 배진모
	 * @see 목록 리턴의 기본적인 쿼리
	 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아래에 있음
	 * @return array $result_object
	 */
	public function dbList($data)
	{
		$db = Database::connect(); // 데이터베이스 연결

		// 유동적인 값에 대한 null 선언 먼저
		$db_where = null;
		$db_like = null;
		$db_grouplike = null;
		$db_limit = 0;
		$db_offset = 0;
		$db_order = null;
		$db_paging = null;
		$db_orlike = null;
		$db_in = null;
		$db_notin = null;
		$page_total = 0;
		$db_join = null; // join 문
		$db_selectsum = null; // 필드 합계

		$sum = array(); // 합계 리턴

		// 유동변수 작업
		// null 선언을 위에서 하고 있는 값들에 대해서만 데이터를 새로 넣어서 쿼리빌더 실행시 warning 실행 예방
		foreach($data as $no => $val) {
			${$no} = $val;
		}

		$builder = $db->table($db_table); // 조회할 테이블

		if($db_where != null) { // where문 생성
			foreach($db_where as $no => $val) {
				$builder->where($val[0], $val[1], $val[2]);
			}
		}

		if($db_like != null) { // where 중에서 like문 생성 
			foreach($db_like as $no => $val) {
				$builder->like($val[0], $val[1], $val[2]);
			}
		}

		if($db_orlike != null) { // where 중에서 or like문 생성 
			foreach($db_orlike as $no => $val) {
				$builder->orLike($val[0], $val[1], $val[2]);
			}
		}

		/**
		 * 여러개 Like 확인시
		 * [0] : string 필드명
		 * [1] : string 찾을 텍스트
		 * [3] : string 와일드 카드(%)를 넣을 위치 - none, before, after
		 **/
		if($db_grouplike != null) { // and() 안의 like문
			$builder->groupStart();
			foreach($db_grouplike as $no => $val) {
				$builder->orLike($val[0], $val[1], $val[2]);
			}
			$builder->groupEnd();
		}

		/** 
		 * in문
		 * [0] : string 필드명
		 * [1] : array|Closure 대상 값 배열 또는 서브 쿼리에 대한 익명 함수
		 * [3] : bool 이스케이프할지 여부 
		 **/
		if($db_in != null) { // where 중에서 in문 생성 
			foreach($db_in as $no => $val) {
				$builder->whereIn($val[0], $val[1], $val[2]);
			}
		}

		/**
		 * not in문
		 * [0] : string 필드명
		 * [1] : array|Closure 대상 값 배열 또는 서브 쿼리에 대한 익명 함수
		 * [3] : bool 이스케이프할지 여부
		 **/
		if($db_notin != null) { // where 중에서 not in문 생성
			foreach($db_notin as $no => $val) {
				$builder->whereNotIn($val[0], $val[1], $val[2]);
			}
		}

		/**
		 * join문
		 * [0] : string 결합(Join)할 테이블 이름
		 * [1] : string JOIN ON 조건 - join에서 on 다음 조건문
		 * [3] : string JOIN type - left, right 등
		 **/
		if($db_join != null) { // where 중에서 join문 생성 
			foreach($db_join as $no => $val) {
				$builder->join($val[0], $val[1], $val[2]);
			}
		}

		if($db_order != null) { // 정렬방식 설정
			$builder->orderBy($db_order);
		}

		/**
		 * [0] : string 필드명
		 * [1] : string alias - 결과 값 이름의 별명 
		 **/
		if($db_selectsum != null) { // 필드 sum
			$sum_builder = clone $builder; // 기존 쿼리 복제
			foreach($db_selectsum as $no => $val) { // sum 쿼리문 추가
				$sum_builder->selectSum($val[0], $val[1]);
			}
			
			// 쿼리 실행
			$sum_result = $sum_builder->get();
			$sum_list = $sum_result->getResult();

			foreach($db_selectsum as $no => $val) { // 결과 값
				$sum[$val[1]] = $sum_list[0]->{$val[1]};
			}
			unset($sum_builder); // $sum_builder 제거
		}

		foreach($db_select as $no => $val) { // 있는 값들에 대해서 쿼리 만들기
			$builder->select($val[0], $val[1]);
		}

		// 페이징 정보
		$page = $db_paging["page"]; // 현재 조회한 페이지의 번호
		$rows = $db_paging["rows"]; // 페이지당 데이터의 수

		// limit와 offset을 설정
		$db_limit = $rows;
		$db_offset = ($page-1)*$db_limit;
		$cnt = $builder->countAllResults(false); // limit를 걸기전의 쿼리 전체 실행갯수를 파악하여 페이징 생성에 사용
		//logLastQuery(); // 실행한 쿼리 로그로 남기기

		$builder->limit($db_limit, $db_offset); // limit와 offset을 설정
		$db_result = $builder->get(); // 쿼리 실행
		$db_list = $db_result->getResult();
		//logLastQuery(); // 실행한 쿼리 로그로 남기기

		$model_result = array();
		if($db_limit > 1) { // 페이징을 요청한 경우(1줄 이상의 데이터 조회)
			// 페이징 정보
			$data = array();
			$db_paging["cnt"] = $cnt;
			$db_paging["page_total"] = $page_total;
			$paging_info = $this->getPaging($db_paging); // 페이징 정보

			$model_result["paging"] = $paging_info["paging"];
			$model_result["paging_list"] = $paging_info["paging_list"];
		}

		$model_result["result"] = true;
		$model_result["message"] = "쿼리가 실행되었습니다";
		$model_result["cnt"] = $cnt;
		$model_result["sum"] = $sum; // array
		$model_result["db_list"] = $db_list;
		$model_result["db_offset"] = $db_offset;

		return $model_result;
	}

	public function getPaging($data)
	{
		$page = $data["page"]; // 현재 조회한 페이지의 번호
		$rows = $data["rows"]; // 페이지당 데이터의 수
		$cnt = $data["cnt"]; // 전체 데이터 숫자
		$page_total = $data["page_total"]; // 전체 데이터 숫자
		$page_real_end = ceil($cnt/$rows); // 실제 데이터 기준으로 페이지의 마지막

		$page_cnt = 9; // 페이징처리 할때 하단에 나와야할 페이지의 수
		$page_mid = ceil($page_cnt/2); // 가운데 페이지 구하기

		$page_start = $page-$page_mid; // 시작 페이지 계산
		if($page_start < 1) { // 시작 페이지가 1보다 작은 경우
			$page_start = 1; // 1페이지로 고정
		}

		$page_end = $page_start+$page_cnt-1; // 마지막 페이지 계산
		if($page_end > $page_real_end) { // 마지막 페이지가 실 페이지보다 작으면
			$page_end = $page_real_end; // 마지막 페이지는 실제 데이터의 마지막으로 계산
		}

		$paging_list = array(); // 페이징 배열 생성
		for($i = $page_start; $i <= $page_end; $i++) {
			$on = "";
			if($i == $page) { // 현재 페이지와 같으면 on으로 강조 처리 하는것
				$on = "active";
			}
			array_push($paging_list, ["page"=>$i, "on"=>$on]);
			if($i == $page_real_end) { // 데이터의 마지막 페이지에 도달하면
				break;
			}
		}

		// 이전 페이지 계산
		if($page == 1) { // 1페이지인 경우
			$page_prev = 1; // 1페이지로 고정
		} else {
			$page_prev = $page-1; // 이전 페이지
		}

		// 다음 페이지 계산
		if($page == $page_total) { // 마지막 페이지인 경우
			$page_next = $page_total; // 마지막 페이지로 고정
		} else {
			$page_next = $page+1; // 이전 페이지
		}

		$paging["page_cnt"] = $page_cnt;
		$paging["page_start"] = $page_start;
		$paging["page_end"] = $page_end;
		$paging["page_total"] = $page_total;
		$paging["page"] = $page;
		$paging["page_prev"] = $page_prev;
		$paging["page_next"] = $page_next;
		$paging["paging_list"] = $paging_list;

		$paging_info = array();
		$paging_info["paging"] = $paging;
		$paging_info["paging_list"] = $paging_list;

		return $paging_info;
	}

	/**
	 * @author 배진모
	 * @see 한줄 반환의 기본적인 쿼리
	 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아래에 있음
	 * @return array $result_object
	 */
	public function dbView($data)
	{
		$data["db_limit"] = 1; // 1건만 불러올 것이므로
		$data["db_offset"] = 0; // 1건만 불러올 것이므로 오프셋을 0으로 고정
		$db_result = $this->dbList($data);
		$db_view = isset($db_result["db_list"][0]) == null ? null : $db_result["db_list"][0];

		$cnt = $db_result["cnt"];

		$proc_result = array();
		$proc_result["result"] = true;
		$proc_result["message"] = "쿼리가 실행되었습니다";
		$proc_result["cnt"] = $cnt;
		$proc_result["db_view"] = $db_view;

		return $proc_result;
	}

	/**
	 * @author 배진모
	 * @see 쿼리 전체 문장으로 실행하여 목록으로 반환
	 * @param char $sql - 실행해야할 쿼리
	 * @return array $query_result
	 */
	public function dbListQuery($sql)
	{
		$db = Database::connect(); // 데이터베이스 연결

		$db_result = $db->query($sql);
		$query_result = $db_result->getResult();

		return $query_result;
	}

	/**
	 * @author 배진모
	 * @see 쿼리 전체 문장으로 실행하여 한줄 반환
	 * @param char $sql - 실행해야할 쿼리
	 * @return array $query_result
	 */
	public function dbViewQuery($sql)
	{
		$query_result = $this->dbListQuery($sql);
		$query_result = $query_result[0];

		return $query_result;
	}

	/**
	 * @author 배진모
	 * @see 결과를 반환하지 않는 쿼리의 실행
	 * @param char $sql - 실행해야할 쿼리
	 * @return array $query_result
	 */
	public function dbUpdateQuery($sql)
	{
		$db = Database::connect(); // 데이터베이스 연결

		$db_result = $db->query($sql);

		return $db_result;
	}

	/**
	 * @author 배진모
	 * @see 데이터 입력(insert)
	 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
	 * @return array $model_result - DB입력 결과와 insert_id와 결과를 반환
	 */
	public function dbInsert($data)
	{
		$db = Database::connect(); // 데이터베이스 연결

		$db_table = $data["db_table"];
		$builder = $db->table($db_table);

		$db_column = $data["db_column"];
		foreach($db_column as $no => $val) {
			$builder->set($val[0], $val[1], $val[2]);
		}

		$db_result = $builder->insert();
		$insert_result = $db_result->resultID;
		$insert_id = $db->insertID();
		logLastQuery(); // 현재 쿼리 로그 남기기

		$model_result = array();
		$model_result["result"] = $insert_result;
		$model_result["proc_mode"] = "insert";
		$model_result["insert_id"] = $insert_id;
		$model_result["affected_rows"] = 0;

		return $model_result;
	}

	/**
	 * @author 배진모
	 * @see 데이터 수정(update)
	 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
	 * @return array $model_result - DB입력 결과와 영향받은 줄 수(affected_rows) 반환
	 */
	public function dbUpdate($data)
	{
		$db = Database::connect(); // 데이터베이스 연결

		$db_table = $data["db_table"];
		$builder = $db->table($db_table);

		$db_column = $data["db_column"];
		$db_where = $data["db_where"];
		foreach($db_column as $no => $val) {
			$builder->set($val[0], $val[1], $val[2]);
		}
		foreach($db_where as $no => $val) {
			$builder->where($val[0], $val[1], $val[2]);
		}
		$db_result = $builder->update();
		$affected_rows = $db->affectedRows();

		logLastQuery(); // 현재 쿼리 로그 남기기

		$model_result = array();
		$model_result["result"] = $db_result;
		$model_result["proc_mode"] = "update";
		$model_result["affected_rows"] = $affected_rows;
		$model_result["insert_id"] = 0;

		return $model_result;
	}

	/**
	 * @author 배진모
	 * @see 데이터 삭제(delete), 실제 데이터의 삭제 이므로 사용에 주의한다
	 * @param array $data - 각종 입력데이터, 각 입력 데이터들에 대한 분해는 아애레 있음
	 * @return array $db_result - DB입력 결과
	 */
	public function dbDelete($data)
	{
		$db = Database::connect(); // 데이터베이스 연결

		$db_table = $data["db_table"];
		$builder = $db->table($db_table);

		$db_where = $data["db_where"];
		foreach($db_where as $no => $val) {
			$builder->where($val[0], $val[1], $val[2]);
		}
		$db_result = $builder->delete();
		$affected_rows = $db->affectedRows();

		logLastQuery(); // 현재 쿼리 로그 남기기

		$model_result = array();
		$model_result["result"] = $db_result;
		$model_result["proc_mode"] = "delete";
		$model_result["affected_rows"] = $affected_rows;
		$model_result["insert_id"] = 0;

		return $model_result;
	}

}