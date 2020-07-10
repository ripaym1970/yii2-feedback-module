<?php

namespace egor260890\feedback\entities;

interface FeedbackInterface {

    function getName():string ;

    function getTel():string ;

    function getCompany_name():string ;

    function getEmail():string ;

    function getMessage():string ;
}
