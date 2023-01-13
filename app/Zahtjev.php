<?php

namespace App;

class Zahtjev
{
    public $NoviObjekat;
}

class NoviObjekat
{
    public $Kupac;
    public $StavkeRacuna;
    public $VrstePlacanja;
}

class StavkeRacuna  
{
    public $RacunStavka;
}

class Kupac
{
    public $IDbroj;
    public $Naziv;
    public $Adresa;
    public $PostanskiBroj;
    public $Grad;
}

class RacunStavka
{
    public $Artikal;
    public $Kolicina;
    public $Rabat;
}

class Artikal
{
    public $Sifra;
    public $Naziv;
    public $JM;
    public $Cijena;
    public $Stopa;
    public $Grupa;
    public $PLU;
}

class VrstePlacanja
{
    public $VrstaPlacanja;
}

class VrstaPlacanja
{
    public $Oznaka;
    public $Iznos;
}

class Odgovori
{
    public $Odgovor;
}

class Odgovor
{
    public $Naziv;
    public $Vrijednost;
}
