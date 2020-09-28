<?php

namespace console\page_objects;

/**
 * page object model для страницы со спиком книг.
 */
class BooksPage
{
    public static $URL = "https://readrate.com/rus/ratings/top100";

    public static $booksList = "//div[contains(@class,'books-list')]";
    public static $bookItem = "//div[contains(@class,'list-item')]";
    public static $bookName = ".//div[contains(@class,'book')]//div[contains(@class,'title')]";
    public static $bookAuthor = ".//div[contains(@class,'book')]//li[contains(@class,'contributor')]";
    public static $bookPicture = ".//div[contains(@class,'book')]//div[contains(@class,'picture')]//img";
    public static $bookRating = ".//div[contains(@class,'item-book-rating')]";
}