<?php
/*
Author: Carlos Quintero
Email: info.fxstudios@gmail.com
HITCEL Corporation
	Valencia - Venezuela
*/
class Querys
{
	private $user = "";
	private $pass = "";
	private $host = "";
	private $base = "";

	const INSERT   = "INSERT INTO ";
	const UPDATE   = "UPDATE ";
	const DELETE   = "DELETE ";
	const VALUES   = " VALUES ";
	const SET      = " SET ";
	const SELECT   = "SELECT ";
	const FROM     = " FROM ";
	const WHERE    = " WHERE ";
	const ORWHERE  = " OR ";
	const ANDWHERE = " AND ";
	const ORDERBY  = " ORDER BY ";
	const GROUPBY  = " GROUP BY ";
	const JOIN     = " JOIN ";
	const ON       = " ON ";
	const COUNT    = "COUNT";
	const BETWEEN    = "BETWEEN";

	function __construct($user, $pass, $host, $base)
	{
		$this->con = new MySQLi($host, $user, $pass, $base);
		if($db->connect_error) {
			die('Conection error ('.$db->connect_errno.')'
				.$db->connect_errno);
		};
	}//

	//Log errors in a log file
	function errorGeneral($X){
		$file = __DIR__.'/errorQuerys.log';
		$f = fopen($file,"a");

		if(is_array($X)){
			foreach ($X as $key => $value) {
				fwrite($f, "[".date("Y-m-d H:i:s")."]".$value . PHP_EOL);
			}
		} else{
			$content = "[".date("Y-m-d H:i:s")."] Error ".$X;
			fwrite($f, $content . PHP_EOL);
		}
		fclose($f);
	}//end

	//Function is used to make a select without wildcards
	function select($X){
		$field = (array_key_exists('field', $X) && !empty($X['field'])) ? $X['field'] : '*';
		$table = (array_key_exists('table', $X)) ? $X['table'] : false;

		$this->con->commit(false);

		try{
			if(!$table){
				throw new Exception("Table not specified", 1);
			}

			$a = $this->con->query(self::SELECT . $field . self::FROM . $table);

			if(!$a){
				throw new Exception($this->con->error, 1);
			}

			$this->con->commit();
			return $a;

		}catch(Exception $e){
			$this->con->rollback();
			$this->error($e->getMessage().' Line:'.$e->getLine());
			return false;
		}
	}//end

	function selectWhere($X){
		$table = (array_key_exists('table',$X)) ? $X['table'] : false ;
		$field = (array_key_exists('where',$X)) ? $X['where'] : false ;
		$select = (array_key_exists('select',$X)) ? $X['select'] : false ;

		$this->con->commit(false);

		try{
			if(!$table || !$field || !$select){
				throw new Exception("Data is missing for consultation", 1);
			}
			$fields = explode(",",$field);
			$c = count($fields);
				if($c>1){
					$where = "";
					$i = 1;
					foreach ($fields as $key => $value) {
						$x = explode("=",$value);
						$where .= $x[0] . "= '".$x[1] . "'";
						if($i < $c){
							$where .= self::ANDWHERE;
						}
						$i++;
					}
				}else{
					$where = "";
					$x = explode("=",$field);
					$where .= $x[0] . "= '".$x[1] . "'";
				}


			$a = $this->con->query(self::SELECT . $select . self::FROM . $table . self::WHERE . $where);

			if(!$a){
				throw new Exception($this->con->error, 1);
			}

			$this->con->commit();
			return $a;

		}catch(Exception $e){
			$this->con->rollback();
			$this->error($e->getMessage().' Line:'.$e->getLine());
			return false;
		}
	}//end

	function update($X){
		$table = array_key_exists('table', $X) ? $X['table'] : false;
		$field = array_key_exists('field', $X) ? $X['field'] : false;
		$where = array_key_exists('where', $X) ? $X['where'] : false;


		$this->con->commit(false);

		try{
			if(!$field){
				throw new Exception("The field to be updated is not specified", 1);
			}
			if(!$table){
				throw new Exception("Table not specified to Update", 1);
			}
			if(!$where){
				throw new Exception("Control where field is not specified", 1);
			}

			$c = explode(",",$field);
				if(count($c)>1){
					$fields = "";
					$i = 1;
					foreach ($c as $key => $value) {
						$x = explode("=",$value);
						$fields .= $x[0]."='".$x[1]."'";
						if($i<count($c)){
							$fields .= ", ";
						}
						$i++;
					}

				}else{
					$fields = "";
					$x = explode("=",$field);
					$fields = $x[0]."='".$x[1]."'";
				}
			
			$w = explode(",",$where);
				if(count($w)>1){
					$wheres = "";
					$i = 1;
					foreach ($w as $key => $value) {
						$y = explode("=",$value);
						$wheres .= $y[0]."='".$y[1]."'";
						if($i<count($w)){
							$wheres .= self::ANDWHERE;
						}
						$i++;
					}

				}else{
					$wheres = "";
					$y = explode("=",$where);
					$wheres = $y[0]."='".$y[1]."'";
				}

			$a = $this->con->query(self::UPDATE . $table . self::SET . $fields . self::WHERE . $wheres);

			if(!$a){
				throw new Exception($this->con->error, 1);
			}

			$this->con->commit();
			return true;

		}catch(Exception $e){
			$this->con->rollback();
			$this->error($e->getMessage().' Line:'.$e->getLine());
			return false;
		}
	}//end

	function delete($X){
		$table = array_key_exists('table', $X) ? $X['table'] : false;
		$where = array_key_exists('where', $X) ? explode("=",$X['where']) : false;


		$this->con->commit(false);

		try{
			if(!$table){
				throw new Exception("Table not specified to Update", 1);
			}
			if(!$where){
				throw new Exception("Control where field is not specified", 1);
			}

			$d = explode(",",$where);
				if(count($w)>1){
					$wheres = "";
					$i = 1;
					foreach ($d as $key => $value) {
						$y = explode("=",$value);
						$wheres .= $x[0]."='".$x[1]."'";
						if($i<count($c)){
							$wheres .= self::ANDWHERE;
						}
						$i++;
					}

				}else{
					$wheres = "";
					$y = explode("=",$where);
					$wheres = $x[0]."='".$x[1]."'";
				}

			$a = $this->con->query(self::DELETE . self::FROM . $table . self::WHERE . $wheres);

			if(!$a){
				throw new Exception($this->con->error, 1);
			}

			$this->con->commit();
			return true;

		}catch(Exception $e){
			$this->con->rollback();
			$this->error($e->getMessage().' Line:'.$e->getLine());
			return false;
		}
	}//end

	function insert($X){
		$table = array_key_exists('table', $X) ? $X['table'] : false;
		$field = array_key_exists('field', $X) ? $X['field'] : false;
		$values = array_key_exists('values', $X) ? $X['values'] : false;


		$this->con->commit(false);

		try{
			if(!$table){
				throw new Exception("Table not specified to Update", 1);
			}
			if(!$field){
				throw new Exception("Fields not specified", 1);
			}
			if(!$values){
				throw new Exception("No values found for insertion", 1);
			}
			$c = count($X['values']);

				if($c>1){

					$group = "";

					$i = 1;
					for($j=0; $j<$c; $j++){
						$x = explode(",",$X['valor'][$j]);
						foreach ($x as $key => $value) {
							$group .= "'".$value."'";
							if($i < count($x)){
								$group .= ", ";
							}
							$i++;
						};
					$a = $this->con->query(self::INSERT . $table . "(".$field.")" . self::VALUES . "(" . $group . ")");
						if(!$a){
							throw new Exception($this->con->error, 1);
						}
						$group = "";
					}

				}else{

					$group = "";

					$i = 1;
					$x = explode(",",$X['valor'][0]);
						foreach ($x as $key => $value) {
							$group .= "'".$value."'";
							if($i < count($x)){
								$group .= ", ";
							}
							$i++;
						};
				$a = $this->con->query(self::INSERT . $table . "(".$field.")" . self::VALUES . "(" . $group . ")");
					if(!$a){
						throw new Exception($this->con->error, 1);
					}
				}

			$this->con->commit();
			return true;

		}catch(Exception $e){
			$this->con->rollback();
			$this->error($e->getMessage().' Line:'.$e->getLine());
			return false;
		}
	}//end

}//end Class

?>
