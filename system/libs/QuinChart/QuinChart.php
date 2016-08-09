<?php

class QuinChart
{

    public function __construct(array $data = array(), $max_score, $scale_steps, $graph_size, $graph_border, $sample_rate)
    {
        $this->pillarcount  = count($data);
        $this->pillardata   = $data;
        $this->graphradius  = $graph_size / 2;
        $this->graphratio   = ($graph_size / 2) / $max_score;
        $this->graphsize    = $graph_size + ($graph_border * 2);
        $this->scalesteps   = $scale_steps;
        $this->samplerate   = $sample_rate < 1 ? 1 : round($sample_rate);
    }

    public function drawGraph()
    {
        $img    = imagecreatetruecolor($this->graphsize * $this->samplerate, $this->graphsize * $this->samplerate);
        $imgr   = imagecreatetruecolor($this->graphsize, $this->graphsize);
        $black  = imagecolorallocate($img, 0, 0, 0);
        $orange = imagecolorallocate($img, 255, 128, 0);

        imagesavealpha($img, true);
        imagesavealpha($imgr, true);

        $trans_colour = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $trans_colour);
        imagefill($imgr, 0, 0, $trans_colour);

        $x = 0;
        for ($i = 0; $i < $this->pillarcount; $i++) {
            $pointx = ($this->graphsize * $this->samplerate) / 2 + round(cos($x * M_PI / 180) * ($this->graphradius * $this->samplerate));
            $pointy = ($this->graphsize * $this->samplerate) / 2 + round(sin($x * M_PI / 180) * ($this->graphradius * $this->samplerate));
            $x = $x + (360 / $this->pillarcount);
            imageline($img, ($this->graphsize * $this->samplerate) / 2, ($this->graphsize * $this->samplerate) / 2, $pointx, $pointy, $black);
        }

        for ($j = 0; $j <= $this->scalesteps; $j++) {
            $x = 0;
            $points = array();
            for ($i = 0; $i < $this->pillarcount; $i++) {
                $points[] = ($this->graphsize * $this->samplerate) / 2 + round(cos($x * M_PI / 180) * (($this->graphradius / $this->scalesteps) * $j) * $this->samplerate);
                $points[] = ($this->graphsize * $this->samplerate) / 2 + round(sin($x * M_PI / 180) * (($this->graphradius / $this->scalesteps) * $j) * $this->samplerate);
                $x = $x + (360 / $this->pillarcount);
            }
            imagepolygon($img, $points, $this->pillarcount, $black);
        }

        $x = 0;
        $points = array();
        foreach ($this->pillardata as $data) {
            $points[] = ($this->graphsize * $this->samplerate) / 2 + round(cos($x * M_PI / 180) * (($data * $this->graphratio) * $this->samplerate));
            $points[] = ($this->graphsize * $this->samplerate) / 2 + round(sin($x * M_PI / 180) * (($data * $this->graphratio) * $this->samplerate));
            $x = $x + (360 / $this->pillarcount);
        }
        imagefilledpolygon($img, $points, $this->pillarcount, $orange);

        $img = imagerotate($img, 90, $trans_colour); //First pillar pointing to top...

        imagecopyresampled($imgr, $img, 0, 0, 0, 0, $this->graphsize, $this->graphsize, $this->graphsize * $this->samplerate, $this->graphsize * $this->samplerate);

        imagepng($imgr, null, 0);
        imagedestroy($img);
        imagedestroy($imgr);
    }



}