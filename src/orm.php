<?php
/*
-> name komicho\orm
-> author komicho
-> version  1.0
-> github https://github.com/komichoLab/orm
*/
namespace komicho;

class orm{

    public function __construct ($host,$user,$pass,$data)
    {
        $this->connect = @mysqli_connect($host,$user,$pass,$data);
        mysqli_set_charset($this->connect,"utf8");
        # Check connection
        if(@mysqli_connect_errno()){
            echo @mysqli_connect_error();
            exit();
        }
    }
    /*
    fun => query
    act => run sql Ordor
    */
    public function query ($sql)
    {
        return $result = @mysqli_query($this->connect,$sql);
        if(!$result){
            return false;
        }else{
            return true;
        }
	}
    /*
    fun => table
    act => scope var table
    */
    public function table ($table)
    {
        $this->table = $table;
        return $this;
    }
    /*
    fun => insert
    act => Write format a insert sql
    */
    public function insert ($array)
    {
        $insert = 'INSERT INTO `' . $this->table . '` (';
        foreach ($array as $key => $value) {
            $insert .= $key . ', ';
        }
        $insert = mb_substr($insert,0,-2);
        $insert .= ') VALUES ( ';
        foreach ($array as $key => $value) {
            $insert .= '"' . $value . '", ';
        }
        $insert = mb_substr($insert,0,-2) . ')';
        
        $this->sql = $insert;
        // return obj
        return $this;
    }
    /*
    fun => update
    act => Write format a update sql
    */
    public function update ($arr)
    {
        $update = 'UPDATE `' . $this->table . '` SET ';
        foreach( $arr as $key => $value ){
            $update .= "$key='$value', ";
        }
        $update = mb_substr($update,0,-2);
        // this var sql
        $this->sql = $update;
        // return obj
        return $this;
    }
    /*
    fun => delete
    act => Write format a delete sql
    */
    public function delete ()
    {
        $delete = 'DELETE FROM `' . $this->table . '`';
        // this var sql
        $this->sql = $delete;
        // return obj
        return $this;
    }
    /*
    fun => select
    act => Write format a select sql
    */
    public function select ($arr = false)
    {
        if( $arr == false ){
            $select = '*';
        }else{
            $_select = '';
            foreach($arr as $key => $value){
                $_select .= $value . ', ';
            }
            $select = mb_substr($_select,0,-2);
        }
        $this->sql = 'SELECT ' . $select . ' FROM `' . $this->table .'`';
        $this->select = $select;
        // return obj
        return $this;
    }
    /*
    fun => as
    act => Write format a as sql
    */
    public function as ($arr)
    {
        $_as = ', ';
        foreach($arr as $key => $value){
            $_as .= $key . ' AS ' . $value . ', ';
        }
        $as = mb_substr($_as,0,-2);
        $this->sql = 'SELECT ' . $this->select . $as . ' FROM `' . $this->table .'`';
        return $this;
    }
    /*
    fun => join
    act => Write format a join sql
    */
    public function join ($table,$arr)
    {
        $_join = ' inner join `' . $table . '` on ';
        foreach($arr as $key => $value){
            $_join .= $key . ' = ' . $value . ' && ';
        }
        $join = mb_substr($_join,0,-4);
        $this->sql = $this->sql . $join;
        return $this;
    }
    /*
    fun => where
    act => Write format a where sql
    */
    public function where ($type,$arr_where)
    {
        $where = ' WHERE ';
        foreach($arr_where as $key => $value){
            $typeValue = gettype($value); // integer
            if ( $typeValue == 'integer' ) {
                $value = $value;
            } else {
                $value = "'$value'";
            }
            $where .= '`' . $key . '`' . ' = ' . "$value " . $type . ' ';
        }
        $where = mb_substr($where,0,-3);
        $this->sql = $this->sql . $where;
        // return obj
        return $this;
    }
    /*
    fun => limit
    act => Write format a limit sql
    */
    public function limit($y,$x=false){
        if(!empty($x)){
            $limit = ' LIMIT ' . $y . ',' . $x;
        }else{
            $limit = ' LIMIT ' . $y;
        }
        $this->sql = $this->sql . $limit;
        // return obj
        return $this;
    }
    /*
    fun => run
    act => run sql order
    */
    public function run ($out = false)
    {
        if ( $out == false ) {
            $this->query = self::query( $this->sql );
            return $this;
        } else if ( $out == 'sql' ) {
            echo $this->sql;
        }
    }
    /*
    fun => ex
    act => check by where
    */
    public function ex ()
    {
        if(mysqli_num_rows($this->query) > 0){
            return true;
        }else{
            return false;
        }
    }
    /*
    fun => num
    act => get number rows by where
    */
    public function num ()
    {
        $rowcount = mysqli_num_rows($this->query);
        echo $rowcount;
    }
    /*
    fun => array
    act => call mysqli_fetch_array
    */
    public function array ()
    {
        return mysqli_fetch_array($this->query);
    }
    /*
    fun => assoc
    act => call mysqli_fetch_assoc
    */
    public function assoc ()
    {
        return mysqli_fetch_assoc($this->query);
    }
    /*
    fun => object
    act => call mysqli_fetch_object
    */
    public function object ()
    {
        return mysqli_fetch_object($this->query);
    }
    /*
    fun => row
    act => call mysqli_fetch_row
    */
    public function row ()
    {
        return mysqli_fetch_row($this->query);
    }
    /*
    fun => json
    act => return josn data from fetch
    */
    public function json ($call)
    {
        $json = $this->$call();
        return json_encode($json);
    }
    
    ##################### box function #####################
    public function __call($method, $args) {
        print("Does not exist Method <b>$method</b> in class: " . __CLASS__);
    }
    ##################### clos function #####################
    
}
