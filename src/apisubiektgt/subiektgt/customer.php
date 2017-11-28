<?php
namespace APISubiektGT\SubiektGT;
use APISubiektGT\SubiektGT\SubiektObj;
use COM;

class Customer extends SubiektObj{	
	protected $customerGt = false;	
	protected $email;
	protected $ref_id;
	protected $firstname;
	protected $lastname;
	protected $post_code;
	protected $city;
	protected $tax_id;
	protected $company_name;
	protected $address;
	protected $address_no;
	protected $phone = false;
	protected $is_company = false;
	


	public function __construct($subiektGt,$customerDetail = array()){						
		parent::__construct($subiektGt, $customerDetail);
		$this->excludeAttr('customerGt');

		if(isset($customerDetail['ref_id'])){
			$symbol = trim($customerDetail['ref_id']);
		}
		if($subiektGt->Kontrahenci->Istnieje($symbol)){
			$this->customerGt = $subiektGt->Kontrahenci->Wczytaj($symbol);
			$this->getGtObject();
			$this->is_exists = true;			
		}
			
	}

	protected function setGtObject(){
		$this->customerGt->Symbol = $this->ref_id;		
		if($this->is_company){			
			$this->customerGt->NazwaPelna = $this->company_name;
			$this->customerGt->Nazwa = substr($this->company_name,0,100);
			$this->customerGt->Osoba = 0;
			$this->customerGt->NIP =  $this->tax_id;

		}else{
			$this->customerGt->Osoba = 1;
			$this->customerGt->OsobaImie = $this->firstname;
			$this->customerGt->OsobaNazwisko = $this->lastname;
			$this->customerGt->NazwaPelna = $this->firstname.' '.$this->lastname;
		}		
		$this->customerGt->Email = $this->email;
		$this->customerGt->Miejscowosc = $this->city;
		$this->customerGt->KodPocztowy = $this->post_code;
		$this->customerGt->Ulica = $this->address;
		$this->customerGt->NrDomu = $this->address_no;

		if($this->phone){
			if($this->customerGt->Telefony->Liczba==0){
				$phoneGt = $this->customerGt->Telefony->Dodaj($this->phone);	
			}else{
				$phoneGt = $this->customerGt->Telefony->Element(1);

			}
			$phoneGt->Nazwa = 'Primary';
			$phoneGt->Numer = $this->phone;
			$phoneGt->Typ = 3;
		}
	}

	protected function getGtObject(){
		$this->gt_id = $this->customerGt->Identyfikator;
		$this->ref_id = $this->customerGt->Symbol;		
		$this->company_name = $this->customerGt->NazwaPelna;
		$this->tax_id = $this->customerGt->NIP;
		$this->firstname = $this->customerGt->OsobaImie;		
		$this->lastname = $this->customerGt->OsobaNazwisko;						
		$this->email = $this->customerGt->Email;
		$this->city = $this->customerGt->Miejscowosc;
		$this->post_code = $this->customerGt->KodPocztowy;
		$this->address = $this->customerGt->Ulica;
		$this->address_no =$this->customerGt->NrDomu;
		
		if($this->customerGt->Telefony->Liczba>0){
			$phoneGt = $this->customerGt->Telefony->Element(1);
			$this->phone = $phoneGt->Numer;
		}								
	}

	public function add(){
		$this->customerGt = $this->subiektGt->Kontrahenci->Dodaj();
		$this->setGtObject();		
		$this->customerGt->Zapisz();
	}

	public function update() {
		$this->setGtObject();
		$this->customerGt->Zapisz();	
	}

	public function getGt(){
		return $this->customerGt;
	}

}
?>