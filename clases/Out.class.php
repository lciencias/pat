<?php
class Out
{
	var $pathWeb;
	var $pathSis;
	var $data;
	var $xml;
	var $xmlPor;
	var $bufferTabla;
	var $bufferGrafica;
	var $bufferGraficaB;
	
	function __construct($pathWeb,$pathSis,$data,$xml,$xmlPor){
		$this->pathWeb = $pathWeb;	
		$this->pathSis = $pathSis;
		$this->data    = $data;
		$this->xml     = $xml;
		$this->xmlPor  = $xmlPor;
		$this->bufferTabla   = "";
		$this->bufferGrafica = $this->bufferGraficaB = "";
		$this->generaExcel();
		$this->generaPastel();
		$this->generaBarras();
	}
	
	
    function generaExcel()
    {
    	$num = rand(1,100000);
        $archivo    = $this->pathSis."tmp/Grafico".$num.".xls";
        $archivoWeb = $this->pathWeb."tmp/Grafico".$num.".xls";
        $this->bufferTabla = '<button type="button" class="btn btn-default btn-xs" onclick="location=\''.$archivoWeb.'\'" target="_blank">Descargar Archivo</button>';       
		try{
	        if(file_exists($archivo)){
	            unlink($archivo);
	        }
	        $f = fopen($archivo,'w+');
	        fwrite($f, utf8_decode($this->data));
			fclose($f);
		}
		catch(Exception $e){
			$this->bufferTabla = $e->getMessage();
		}
    }
    
    function generaPastel()
    {
    	$contador_div = rand(1,100000);
        $this->bufferGrafica = "
            <div id='RecentActivityDiv".$contador_div."' align='center'></div>
                <div id=\"fcexpDiv".$contador_div."\" class=\"center\"></div>
                    <script type=\"text/javascript\">
                        var myChart = new FusionCharts('".$this->pathWeb."estadisticas/js_fusion/Pie3D.swf', 'RecentActivityDiv".$contador_div."','350', '350', '0', '1');
                        myChart.setDataXML(\"".$this->xml."\");
                        myChart.setTransparent(true);
                        myChart.render(\"RecentActivityDiv".$contador_div."\");
                        
                    </script>
                 </div>
              </div>";
/*
 var myExportComponent = new FusionChartsExportObject(\"fcExporter".$contador_div."\", \"".$this->pathWeb."estadisticas/js_fusion/FCExporter.swf\");
                        myExportComponent.Render(\"fcexpDiv".$contador_div."\")
 */        
    }

    function generaBarras()
    {
    	$contador_div = rand(1,100000);
    	$this->bufferGraficaB = "
            <div id='RecentActivityDiv".$contador_div."' align='center'></div>
                <div id=\"fcexpDiv".$contador_div."\" class=\"center\"></div>
                    <script type=\"text/javascript\">
                        var myChart = new FusionCharts('".$this->pathWeb."estadisticas/js_fusion/Column2D.swf', 'RecentActivityDiv".$contador_div."','480', '300', '0', '1');
                        myChart.setDataXML(\"".$this->xmlPor."\");
                        myChart.setTransparent(true);
                        myChart.render(\"RecentActivityDiv".$contador_div."\");
                    </script>
                 </div>
              </div>";
    	//var myExportComponent = new FusionChartsExportObject(\"fcExporter".$contador_div."\", \"".$this->pathWeb."estadisticas/js_fusion/FCExporter.swf\");
        //myExportComponent.Render(\"fcexpDiv".$contador_div."\");
    			 
    }
	
	public function getBufferTabla() {
		return $this->bufferTabla;
	}
	
	public function getBufferGrafica() {
		return $this->bufferGrafica;
	}
	public function getBufferGraficaB(){
		return $this->bufferGraficaB;
	}
}

?>
