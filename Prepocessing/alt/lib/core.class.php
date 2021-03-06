<?php
if(!defined('core')){
    exit('No Dice!');
}

class Core extends Database
{
    protected $link;
    
    public $error = '';
    public $success = '';
    function __construct()
    {
        $this->link = parent::connect();
    }

    function cekQuran(){
        $query = $this->link->query("SELECT idAyat, Terjemahan FROM albaqarah where idAyat=31");
        $result = mysqli_num_rows($query);
        return $query;
    }
    function cariStopword($kata){
        //var_dump($kata);
        $sql = $this->link->query("SELECT stopword FROM stopword WHERE stopword='$kata'");
        $result = mysqli_fetch_assoc($sql);
        return $result["stopword"];
    }
    function cari($kata){
        $sql = $this->link->query("SELECT count(katadasar) jmlh FROM katadasar WHERE katadasar='$kata' limit 1");
        $result = mysqli_fetch_assoc($sql);
        return $result["jmlh"];
    }

//langkah 1 - hapus partikel
    function hapuspartikel($kata){
        if((substr($kata, -3) == 'kah' )||( substr($kata, -3) == 'lah' )||( substr($kata, -3) == 'pun' )){
            $kata = substr($kata, 0, -3);           
        }
        return $kata;
    }

//langkah 2 - hapus possesive pronoun
    function hapuspp($kata){
        if(strlen($kata) > 4){
            if((substr($kata, -2)== 'ku')||(substr($kata, -2)== 'mu')){
                $kata = substr($kata, 0, -2);
            }else if((substr($kata, -3)== 'nya')){
                $kata = substr($kata,0, -3);
            }
        }
        return $kata;
    }

//langkah 3 hapus first order prefiks (awalan pertama)
    function hapusawalan1($kata){
        if(substr($kata,0,4)=="meng"){
            if(substr($kata,4,1)=="e"||substr($kata,4,1)=="u"){
                $kata = "k".substr($kata,4);
            }else{
                $kata = substr($kata,4);
            }
        }else if(substr($kata,0,4)=="meny"){
            $kata = "ny".substr($kata,4);
        }else if(substr($kata,0,3)=="men"){
            $kata = substr($kata,3);
        }else if(substr($kata,0,3)=="mem"){
            if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
                $kata = "p".substr($kata,3);
            }else{
                $kata = substr($kata,3);
            }
        }else if(substr($kata,0,2)=="me"){
            $kata = substr($kata,2);
        }else if(substr($kata,0,4)=="peng"){
            if(substr($kata,4,1)=="e" || substr($kata,4,1)=="a"){
                $kata = "k".substr($kata,4);
            }else{
                $kata = substr($kata,4);
            }
        }else if(substr($kata,0,4)=="peny"){
            $kata = "s".substr($kata,4);
        }else if(substr($kata,0,3)=="pen"){
            if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
                $kata = "t".substr($kata,3);
            }else{
                $kata = substr($kata,3);
            }
        }else if(substr($kata,0,3)=="pem"){
            if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
                $kata = "p".substr($kata,3);
            }else{
                $kata = substr($kata,3);
            }
        }else if(substr($kata,0,2)=="di"){
            $kata = substr($kata,2);
        }else if(substr($kata,0,5)=="keter"){
            $kata = substr($kata,5);
        }else if(substr($kata,0,3)=="ter"){
            $kata = substr($kata,3);
        }else if(substr($kata,0,2)=="ke"){
            $kata = substr($kata,2);
        }
        return $kata;
    }
//langkah 4 hapus second order prefiks (awalan kedua)
    function hapusawalan2($kata){
        if(substr($kata,0,3)=="ber"){
            $kata = substr($kata,3);
        }else if(substr($kata,0,3)=="bel"){
            $kata = substr($kata,3);
        }else if(substr($kata,0,2)=="be"){
            $kata = substr($kata,2);
        }else if(substr($kata,0,3)=="per" && strlen($kata) > 5){
            $kata = substr($kata,3);
        }else if(substr($kata,0,2)=="pe"  && strlen($kata) > 5){
            $kata = substr($kata,2);
        }else if(substr($kata,0,3)=="pel"  && strlen($kata) > 5){
            $kata = substr($kata,3);
        }else if(substr($kata,0,2)=="se"  && strlen($kata) > 5){
            $kata = substr($kata,2);
        }
        return $kata;
    }
////langkah 5 hapus suffiks
    function hapusakhiran($kata){
        if (substr($kata, -3)== "kan" ){
            $kata = substr($kata, 0, -3);
        }
        else if(substr($kata, -1)== "i" ){
            $kata = substr($kata, 0, -1);
        }else if(substr($kata, -2)== "an"){
            $kata = substr($kata, 0, -2);
        }
        return $kata;
    }
    ## TRY STEMMING 1
    /*function stemming($word){
        $array = explode(" ",$word);
        foreach($array as $yuk){
            if($this->cari($yuk)==1){
                $hasil[] = $yuk;
            }else{
                $hasil[] = $this->hapusakhiran($this->hapusawalan2($this->hapusawalan1($this->hapuspp($this->hapuspartikel($yuk)))));
            }
        }
        return implode(" ",$hasil);
    }*/
    ##TRY STEMMING 2
    function stemming1($word){
        $array = explode(" ",$word);
        foreach($array as $yuk){
            if($this->cari($yuk)==1){
                $hasil[] = $yuk;
            }else{
                $hasil[] = $this->hapuspp($yuk);
            }
        }
        return implode(" ",$hasil);
    }
    function stemming2($word){
        $array = explode(" ",$word);
        foreach($array as $yuk){
            if($this->cari($yuk)==1){
                $hasil[] = $yuk;
            }else{
                $hasil[] = $this->hapuspartikel($yuk);
            }
        }
        return implode(" ",$hasil);
    }

    function stemming3($word){
        $array = explode(" ",$word);
        foreach($array as $yuk){
            if($this->cari($yuk)==1){
                $hasil[] = $yuk;
            }else{
                $hasil[] = $this->hapusawalan1($yuk);
            }
        }
        return implode(" ",$hasil);
    }

    function stemming4($word){
        $array = explode(" ",$word);
        foreach($array as $yuk){
            if($this->cari($yuk)==1){
                $hasil[] = $yuk;
            }else{
                $hasil[] = $this->hapusawalan2($yuk);
            }
        }
        return implode(" ",$hasil);
    }

    function stemming5($word){
        $array = explode(" ",$word);
        foreach($array as $yuk){
            if($this->cari($yuk)==1){
                $hasil[] = $yuk;
            }else{
                $hasil[] = $this->hapusakhiran($yuk);
            }
        }
        return implode(" ",$hasil);
    }
    ## TRY STEMMING 3
    /*function stemming($string){
        $word = explode(" ",$string);
        var_dump($word);
        while ($row = mysqli_fetch_array($word)) {
            # code...
            if ($this->cari($word)==1) {
                # code...
                return $word;
            }
            else {
                $word = $this->hapuspp($word);
                if ($this->cari($word)==1) {
                # code...
                    return $word;
                }
                $word = $this->hapuspartikel($word);
                if ($this->cari($word)==1) {
                # code...
                    return $word;
                }
                $word = $this->hapusawalan1($word);
                if ($this->cari($word)==1) {
                # code...
                    return $word;
                }
                $word = $this->hapusawalan2($word);
                if ($this->cari($word)==1) {
                # code...
                    return $word;
                }
                $word = $this->hapusakhiran($word);
                if ($this->cari($word)==1) {
                # code...
                    return $word;
                }
            }
        }
        return implode(" ", $word);
    }*/

    function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
       $string = str_replace('-', ' ', $string); 
       return $string;
   }

   function stopword($string){
    $array = explode(" ",$string);
    foreach($array as $yuk){
        if($this->cariStopword($yuk)!=$yuk){
            $hasil[] = $yuk;
        }
    }
    return implode(" ",$hasil);
}
}

?>
