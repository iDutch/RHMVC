<?php

class DashboardController extends AbstractController
{
    use ACLTrait;

    public function admin_indexAction()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /admin/login");
        }

        $view = new View(__DIR__ . '/../../application/views/dashboard/admin_index.phtml');

        return $view->parse();
    }

    public function signatureAction()
    {
        $lines = array(
            'ASCII stupid question, get a stupid ANSI!',
            'Bad or missing mouse driver. Spank the cat [Y/N]?',
            'A user friendly computer first requires a friendly user.',
            'Make it idiot proof and somebody will build a better idiot...',
            'Disclaimer: Any errors in spelling, tact, or fact are transmission errors.',
            'Never say "OOPS!" always say "Ah, Interesting!"',
            'Warning, keyboard not found. Press Enter to continue.',
            'There are never enough hours in a day, but always too many days before Saturday.',
            'Math problems? Call 1-800-[(10x)(ln(13e))]-[sin(xy)/2.362x]',
            'Maintenance-free: When it breaks, it can\'t be fixed...',
            'You never finish a program, you just stop working on it.',
        );

        $img = imagecreatetruecolor(620, 20);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagesavealpha($img, true);

        $trans_colour = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $trans_colour);

        imagettftext($img, 12, 0, 5, 15, $black, __DIR__ . '/../../docs/static/fonts/Cabin-Regular.ttf', $lines[rand(0,10)]);

        //$view = new View(__DIR__ . '/../../application/views/dashboard/empty.phtml');

        //return $view->parse();
        header('Content-Type: image/png');

        echo imagepng($img, null, 0);
        imagedestroy($img);
        exit;
    }

}