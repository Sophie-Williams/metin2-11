<?php

class pagination
{

	public $total;
	public $limit;
	public $scroll = 10;
	public $request;
	public $previous_text = 'Geri';
	public $next_text = 'İleri';
	public $first_text = 'İlk';
	public $end_text = 'Son';
	public $start;
	public $uzanti = ".html";

	private $page_num;
	private $sayfala;

    /*
     *  Sayfalama Fonksiyonu
     *  @return string
     */
	 
	 function start(){
		 
		$sayfa = gvn::get($this->request);
		if(!$sayfa){return 0;}else{return $start = ( $sayfa - 1 ) * $this->limit;}	
		}
	 
    function Paginate(){
		
		$this->sayfala = '<div class="btn-group">';
		
		$this->page_num = !empty($_GET[$this->request]) ? $_GET[$this->request] : NULL;
		
		if($this->page_num == '' or !is_numeric($this->page_num) or !intval($this->page_num)){
            $this->page_num = 1;
        }

		$this->ortalama = ceil($this->total/$this->limit);
		
		if($this->page_num > $this->ortalama){
            $this->page_num = 1;
        }

        $i_previous = NULL;
		if($this->page_num > 1){
			$i_previous = '<a class="btn btn-default" href="'.$this->page.'1'.$this->uzanti.'">'.$this->first_text.'</a>';
		}

        $i_next = NULL;
		if($this->page_num < $this->ortalama){
            $i_next = '<a class="btn btn-default" href="'.$this->page.$this->ortalama.''.$this->uzanti.'">'.$this->end_text.'</a>';
        }

		if($this->page_num <= 1){
            $previous = '<a class="btn btn-default" href="#">'.$this->previous_text.'</a>';
        } else{
            $previous_a = $this->page_num-1;
            $previous = '<a class="btn btn-default" href="'.$this->page.$previous_a.''.$this->uzanti.'">'.$this->previous_text.'</a>';
        }

		if($this->page_num == $this->ortalama){
            $next = '<a class="btn btn-default" href="#">'.$this->next_text.'</a>';
        } else{
            $next_a = $this->page_num + 1;
            $next = '<a class="btn btn-default" href="'.$this->page.$next_a.''.$this->uzanti.'">'.$this->next_text.'</a>';
        }

		$this->sayfala .= $i_previous;
		$this->sayfala .= $previous;

		$pn = ceil($this->page_num/$this->scroll);
		$scroll = $this->scroll * $pn;

		if($this->page_num <= $this->scroll){
            $count = 1;
        } else{
            $count = $pn * $this->scroll - $this->scroll + 1;
        }

		if($scroll > $this->ortalama){
            $scroll = $this->ortalama;
        }
		
		for($i=$count; $i<=$scroll; $i++){
            if($this->page_num == $i){
                $secili = '<a class="btn btn-primary" href="#">'.$i.'</a>';
            } else{
                $secili = '<a class="btn btn-default" href="'.$this->page.$i.''.$this->uzanti.'">'.$i.'</a>';
            }
            $this->sayfala .= $secili;
		}
		$this->sayfala .= $next;
		$this->sayfala .= $i_next;
		$this->sayfala .= '</div>';

        return $this->sayfala;
	}

}

?>
