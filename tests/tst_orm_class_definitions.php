<?php
class ClassWithoutComments {
	protected $id;
	protected $title;
}

/**
 * @access public
 */
class ClassWithoutTableAnnotation {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=int)
	 * @var int
	 */
	protected $id;
}

/**
 * @TmOrmTable(Table1)
 * @access public
 */
class ClassWithoutColumnAnnotations {
	/**
	 * The id - immutable
	 * @var int
	 */
	protected $id;
}
/**
 * @TmOrmTable(Table1)
 * @access public
 */
class ClassWithoutColumnName{
	/**
	 * The id - immutable
	 * @TmOrmColumn(type=id)
	 * @var int
	 */
	protected $id;

}

/**
 * @TmOrmTable(Table1)
 * @access public
 */
class ClassWithoutIdColumn{
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(Table1)
 * @access public
 */
class ClassWithMultipleIdColumns{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The id2
	 * @TmOrmColumn(name=id2,type=id)
	 */
	protected $id2;
}

/**
 * @TmOrmTable(Table1)
 * @access public
 */
class ClassWithTableAndColumns{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(Table1)
 * @access public
 */
class ClassWithoutColumnType{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The title
	 * @TmOrmColumn(name=title)
	 */
	protected $title;
}

/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(class=Category,localProperty=category,remoteProperty=id)
 * @access public
 */
class ClassWithNoRelationType{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Rubbish,class=Category,localProperty=category,remoteProperty=id)
 * @access public
 */
class ClassWithInvalidRelationType{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Single,localProperty=category,remoteProperty=id)
 * @access public
 */
class ClassWithNoRelationClass{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * /**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Single,class=Category,remoteProperty=id)
 * @access public
 */
class ClassWithNoRelationLocalProperty{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * /**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category)
 * @access public
 */
class ClassWithNoRelationRemoteProperty{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupLocal=article_id,lookupRemote=tag_id)
 * @access public
 */
class ClassWithNoRelationLookupTable{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=Articles2Tags,lookupRemote=tag_id)
 * @access public
 */
class ClassWithNoRelationLookupLocal{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=Articles2Tags,lookupLocal=article_id)
 * @access public
 */
class ClassWithNoRelationLookupRemote{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id)
 * @access public
 */
class ClassWithSingleRelation{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article)
 * @access public
 */
class ClassWithListRelation{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=Articles2Tags,lookupLocal=article_id,lookupRemote=tag_id)
 * @access public
 */
class ClassWithLookupRelation{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(Table1)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=Articles2Tags,lookupLocal=article_id,lookupRemote=tag_id)
 * * @access public
 */
class ClassWithAllRelations{
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;
	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(t_xxx)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=t_tags2articles,lookupLocal=article_id,lookupRemote=tag_id)
 * @access public
 */
class InvalidTableArticle extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The title
	 * @TmOrmColumn(name=category,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(t_articles)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=t_tags2articles,lookupLocal=article_id,lookupRemote=tag_id)
 * @access public
 */
class InvalidColumnArticle extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=xxx,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The title
	 * @TmOrmColumn(name=xxx,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(t_articles)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=t_tags2articles,lookupLocal=article_id,lookupRemote=tag_id)
 * @access public
 */
class Article extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The related category
	 * @TmOrmColumn(name=category,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(t_articles)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=t_tags2articles,lookupLocal=article_id,lookupRemote=tag_id)
 * @access public
 */
class ArticleDiffColumnAndPropNames extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The related category
	 * @TmOrmColumn(name=category,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $headline;
}

/**
 * @TmOrmTable(t_articles)
 * @TmOrmRelation(type=Single,class=Category,localProperty=category,remoteProperty=id,cascade=true)
 * @access public
 */
class ArticleWithSingleCascade extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The related category
	 * @TmOrmColumn(name=category,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}

/**
 * @TmOrmTable(t_articles)
 * @TmOrmRelation(type=List,class=Comment,localProperty=id,remoteProperty=article,cascade=true)
 * @access public
 */
class ArticleWithListCascade extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The related category
	 * @TmOrmColumn(name=category,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(t_articles)
 * @TmOrmRelation(type=Lookup,class=Tag,localProperty=id,remoteProperty=id,lookupTable=t_tags2articles,lookupLocal=article_id,lookupRemote=tag_id,cascade=true)
 * @access public
 */
class ArticleWithLookupCascade extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The related category
	 * @TmOrmColumn(name=category,type=int)
	 */
	protected $category;

	/**
	 * The title
	 * @TmOrmColumn(name=title,type=string)
	 */
	protected $title;
}
/**
 * @TmOrmTable(t_categories)
 * @TmOrmRelation(type=List,class=Article,localProperty=id,remoteProperty=category)
 * @access public
 */
class Category extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The category name
	 * @TmOrmColumn(name=name,type=string)
	 */
	protected $name;
}

/**
 * @TmOrmTable(t_comments)
 * @TmOrmRelation(type=Single,class=Article,localProperty=article,remoteProperty=id)
 * @access public
 */
class Comment extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The related article
	 * @TmOrmColumn(name=article,type=int)
	 */
	protected $article;

	/**
	 * The comment
	 * @TmOrmColumn(name=comment,type=string)
	 */
	protected $comment;
}

/**
 * @TmOrmTable(t_tags)
 * @TmOrmRelation(type=Lookup,class=Article,localProperty=id,remoteProperty=id,lookupTable=t_tags2articles,lookupLocal=tag_id,lookupRemote=article_id)
 * @access public
 */
class Tag extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The tag name
	 * @TmOrmColumn(name=name,type=string)
	 */
	protected $name;

}

/**
 * @TmOrmTable(t_dummys)
 * @access public
 */
class Dummy extends OrmObject {
	/**
	 * The id - immutable
	 * @TmOrmColumn(name=id,type=id)
	 * @var int
	 */
	protected $id;

	/**
	 * The name
	 * @TmOrmColumn(name=name,type=string)
	 */
	protected $name;

}
?>