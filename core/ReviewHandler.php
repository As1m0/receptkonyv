<?php

abstract class ReviewHandler
{
    public static function UploadReview(array $data) : void
    {
        DBHandler::RunQuery(
        "INSERT INTO `reviews` (`recept_id`, `felh_id`, `komment`, `ertekeles`) VALUES (?,?,?,?)",
        [ new DBParam(DBTypes::Int, $data["recept_id"]),
        new DBParam(DBTypes::Int, $data["felh_id"]),
        new DBParam(DBTypes::String, $data["komment"]),
        new DBParam(DBTypes::Int, $data["ertekeles"])] );
    }

    public static function DeleteReview(int $kommentID) : void
    {
        DBHandler::RunQuery("DELETE FROM `reviews` WHERE `review_id` = ?", [new DBParam(DBTypes::Int,  $kommentID)]);
    }

}