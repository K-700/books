<?php

namespace console\controllers;

use common\models\Author;
use common\models\Book;
use console\page_objects\BooksPage;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\httpclient\Client;

class ParseController extends Controller
{
    public function actionTopBooks()
    {
        $logCategory = "top_books";
        \Yii::info("Начало парсинга", $logCategory);

        $client = new Client;
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl(BooksPage::$URL)
            ->send();

        if ($response->isOk) {
            $dom = new \DOMDocument;
            // Подавление ошибок о невалидности HTML
            libxml_use_internal_errors(true);
            $dom->loadHTML($response->getContent());
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);
            $booksNodes = $xpath->query(BooksPage::$booksList . BooksPage::$bookItem);
            $index = 0;
            foreach ($booksNodes as $bookNode) {
                $index++;
                try {
                    $authorName = trim($xpath->query(BooksPage::$bookAuthor, $bookNode)[0]->nodeValue);
                    if (!$author = Author::findOne(['name' => $authorName])) {
                        $author = new Author(['name' => $authorName]);
                        if (!$author->save()) {
                            $errors = join(";", $author->getErrorSummary(true));
                            \Yii::error("[{$index}]: {$errors}", $logCategory);
                            continue;
                        }
                    }

                    $bookName = trim($xpath->query(BooksPage::$bookName, $bookNode)[0]->nodeValue);
                    if (!$book = Book::find()
                        ->andWhere(['name' => $bookName])
                        ->andWhere(['author_id' => $author->id])
                        ->one()
                    ) {
                        $book = new Book(['name' => $bookName]);
                    }
                    $book->rating = floatval(str_replace(',', '.', $xpath->query(BooksPage::$bookRating, $bookNode)[0]->nodeValue));
                    // удаляем символы "//" в начале
                    $book->photo_url = substr($xpath->query(BooksPage::$bookPicture, $bookNode)[0]->getAttribute('src'), 2);
                    if (!$book->validate(['photo_url', 'rating', 'name'])) {
                        $errors = join(";", $book->getErrorSummary(true));
                        \Yii::error("[{$index}]: {$errors}", $logCategory);
                        continue;
                    }
                    $book->link('author', $author);
                } catch (\Exception $e) {
                    \Yii::error("[{$index}]: {$e->getMessage()}", $logCategory);
                }
            }
        } else {
            \Yii::error("Неверный ответ от сервиса", $logCategory);
            return ExitCode::UNAVAILABLE;
        }

        if ($index == 0) {
            \Yii::warning("Список книг не найден", $logCategory);
        }
        \Yii::info("Парсинг окончен", $logCategory);
        return ExitCode::OK;
    }
}