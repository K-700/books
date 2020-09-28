<?php

namespace frontend\models;

use common\models\Author;
use common\models\Book;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class BookSearch extends Book
{
    public $authorName;

    public function init()
    {
        ActiveRecord::init();
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['author.name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rating'], 'number'],
            [['name', 'author.name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Book::find()
            ->joinWith('author');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'name',
                    'author.name' => [
                        'asc' => [Author::tableName() . '.name' => SORT_ASC],
                        'desc' => [Author::tableName() . '.name' => SORT_DESC],
                    ],
                    'rating',
                ],
                'defaultOrder' => ['rating' => SORT_DESC]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['rating' => $this->rating])
            ->andFilterWhere(['like', Author::tableName() . '.name', $this->getAttribute('author.name')])
            ->andFilterWhere(['like', self::tableName() . '.name', $this->name]);

        return $dataProvider;
    }
}
