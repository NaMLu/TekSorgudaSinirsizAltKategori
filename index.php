<?php

try {
     $db = new PDO("mysql:host=localhost;dbname=ders", "root", "");
} catch ( PDOException $e ){
     print $e->getMessage();
}

// Dizi oluşturuyoruz.
$tumKategoriler = array();

$sorgu = $db->query("SELECT * FROM kategoriler", PDO::FETCH_ASSOC);
if ( $sorgu->rowCount() ){
     foreach( $sorgu as $satir ){
        $tumKategoriler[$satir['id']]['id'] = $satir['id'];
        $tumKategoriler[$satir['id']]['kategori'] = $satir['kategori'];
        $tumKategoriler[$satir['id']]['ustKat'] = $satir['ustKat'];
     }
}


// Ana kategorileri almak için bir fonksiyon yazıyoruz.
function kategorileriBul($kategoriler)
{
    $dizi = array();
    
    foreach($kategoriler as $kategori)
    {
        if($kategori['ustKat'] == 0) $dizi[] = $kategori;
    }
    return $dizi;
}

// Herhangi bir kategorinin alt kategorilerini bulmak için bir fonksiyon yazıyoruz.
function altKategorileriBul($ustKat,$kategoriler,$tekrar = 2)
{
    // Bir dizi oluşturuyoruz.
    $dizi = array();
    
    // Tüm kategorileri döngüye sokuyoruz.
    foreach($kategoriler as $kategori)
    {
        // Belirtilen kategorilerin alt kategorilerini alıyoruz ve diziye ekliyoruz.
        if($kategori['ustKat'] == $ustKat)
        {
            $dizi[] = $kategori;
        }
    }
    
    // Dizide eleman var mı onu kontrol ediyoruz.
    if(count($dizi) > 0)
    {
        // Dizideki alt kategorileri döngüye sokuyoruz.
        foreach($dizi as $kat)
        {
            // Alt kategorileri yazdırıyoruz.
            echo '<option>'.str_repeat('&nbsp;&nbsp;&nbsp;',$tekrar).' '.$kat['kategori'].'</option>';
            // Yazdırılan kategorinin alt kategorilerini bulduruyoruz. Yani döngü içinde döngü oluşturarak verilerin dallanmasını sağlıyoruz.
            altKategorileriBul($kat['id'],$kategoriler,$tekrar+3);
        }
    }
}

// Ana kategorileri alıyoruz.
$anaKategoriler = kategorileriBul($tumKategoriler);

echo '<select>';
    // Ana kategorileri liste şeklinde alıyoruz.
    foreach($anaKategoriler as $kategori)
    {
        // Ana kategorileri ekrana yazdırıyoruz.
        echo '<option>'.str_repeat('&nbsp;&nbsp;&nbsp;',0).' '.$kategori['kategori'].'</option>';
        // Alt Kategorilerini dallandırarak yazdırıyoruz.
        altKategorileriBul($kategori['id'],$tumKategoriler);
    }
echo '</select>';

$db = null;
?>