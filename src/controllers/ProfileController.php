<?php
require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserProfile.php';
require_once __DIR__.'/../repository/RatingRepository.php';
require_once __DIR__.'/../repository/AnnouncementRepository.php';

class ProfileController extends AppController
{
    private RatingRepository $ratingsRep;
    private AnnouncementRepository $annRep;

    public function __construct()
    {
        parent::__construct();
        $this->ratingsRep = new RatingRepository();
        $this->annRep = new AnnouncementRepository();
    }

    public function profile(){
        $this->userCookieVerification();
        $id = intval($_COOKIE["user"]);
        $profile = $this->userRep->getProfileById($id);
        $ratings = $this->ratingsRep->getRatings($id);
        $anns = $this->annRep->getAnnsById($id);
        return $this->render("profile", ['user'=>$profile, 'ratings' => $ratings, 'anns' => $anns]);
    }
}