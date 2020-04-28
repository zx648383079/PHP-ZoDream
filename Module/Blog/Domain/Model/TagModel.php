<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Zodream\Infrastructure\Support\Html;


/**
 * Class TagModel
 * @package Module\Blog\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $blog_count
 */
class TagModel extends Model {
	public static function tableName() {
        return 'blog_tag';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,40',
            'description' => 'string:0,255',
            'blog_count' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '标签',
            'description' => '说明',
            'blog_count' => 'Blog Count',
        ];
    }

    public static function findIdByName($name) {
	    return static::where('name', $name)->value('id');
    }

    public static function getBlogByName($name) {
	    $id = static::findIdByName($name);
	    if (empty($id)) {
	        return [];
        }
        return TagRelationshipModel::where('tag_id', $id)->pluck('blog_id');
    }

    public static function replaceTag($blog_id, $content) {
        $tags = TagRelationshipModel::where('blog_id', $blog_id)->pluck('tag_id');
        if (empty($tags)) {
            return $content;
        }
        $tags = static::whereIn('id', $tags)->pluck('name');
        $replace = [];
        $i = 0;
        $content = preg_replace_callback('#<code[^\<\>]*>[\s\S]+?</code>#', function ($match) use (&$replace, &$i) {
            $tag = 'ZO'.$i ++.'OZ';
            $replace[$tag] = $match[0];
            return $tag;
        }, $content);
        $content = preg_replace_callback('#<a[^\<\>]+>[\s\S]+?</a>#', function ($match) use (&$replace, &$i) {
            $tag = 'ZO'.$i ++.'OZ';
            $replace[$tag] = $match[0];
            return $tag;
        }, $content);
        $content = preg_replace_callback('#<img[^\<\>]+>#', function ($match) use (&$replace, &$i) {
            $tag = 'ZO'.$i ++.'OZ';
            $replace[$tag] = $match[0];
            return $tag;
        }, $content);
        $content = str_replace($tags, array_map(function ($tag) {
            return Html::a($tag, ['./', 'tag' => $tag]);
        }, $tags), $content);
        return str_replace(array_keys($replace), array_values($replace), $content);
    }

}