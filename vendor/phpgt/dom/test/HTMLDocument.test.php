<?php
namespace Gt\Dom;

class HTMLDocumentTest extends \PHPUnit_Framework_TestCase {

public function testConstruction()
{
// test construction from raw HTML
	$fromRawHTML = new HTMLDocument(test\Helper::HTML);
	$this->assertInstanceOf('Gt\Dom\HTMLDocument', $fromRawHTML);

// test construction from a DOMDocument object
	$domDocument = new \DOMDocument('1.0', 'UTF-8');
	$domDocument->loadHTML(test\Helper::HTML);
	$fromDOMDocument = new HTMLDocument($domDocument);
	$this->assertInstanceOf('Gt\Dom\HTMLDocument', $fromDOMDocument);

// test construction from a HTMLDocument object, just to be sure
	$fromHTMLDocument = new HTMLDocument($fromRawHTML);
	$this->assertInstanceOf('Gt\Dom\HTMLDocument', $fromHTMLDocument);

// test if elements are consistent
	$h2FromRawHTML = $fromRawHTML->querySelector('h2');
	$h2FromDOMDocument = $fromDOMDocument->querySelector('h2');
	$this->assertSame($h2FromRawHTML, $h2FromDOMDocument);

}

public function testInheritance() {
	$document = new HTMLDocument(test\Helper::HTML);
	$this->assertInstanceOf("Gt\Dom\Element", $document->documentElement);
}

public function testRemoveElement() {
	$document = new HTMLDocument(test\Helper::HTML);

	$h1List = $document->getElementsByTagName("h1");
	$this->assertEquals(1, $h1List->length);
	$h1 = $h1List->item(0);
	$h1->remove();

	$h1List = $document->getElementsByTagName("h1");
	$this->assertEquals(0, $h1List->length);
}

public function testQuerySelector() {
	$document = new HTMLDocument(test\Helper::HTML_MORE);
	$h2TagName = $document->getElementsByTagName("h2")->item(0);
	$h2QuerySelector = $document->querySelector("h2");

	$this->assertSame($h2QuerySelector, $h2TagName);
}

public function testQuerySelectorAll() {
	$document = new HTMLDocument(test\Helper::HTML_MORE);
	$pListTagName = $document->getElementsByTagName("p");
	$pListQuerySelector = $document->querySelectorAll("p");

	$this->assertEquals($pListTagName->length, $pListQuerySelector->length);

	for($i = 0, $len = $pListQuerySelector->length; $i < $len; $i++) {
		$this->assertSame(
			$pListQuerySelector->item($i),
			$pListTagName->item($i)
		);
	}
}

public function testHeadElement() {
	$document = new HTMLDocument(test\Helper::HTML_MORE);
	$this->assertInstanceOf("\Gt\Dom\Element", $document->head);
}

public function testHeadElementAutomaticallyCreated() {
// test\Helper::HTML does not explicitly define a <head>
	$document = new HTMLDocument(test\Helper::HTML);
	$this->assertInstanceOf("\Gt\Dom\Element", $document->head);
}

public function testBodyElement() {
	$document = new HTMLDocument(test\Helper::HTML_MORE);
	$this->assertInstanceOf("\Gt\Dom\Element", $document->body);
}

public function testBodyElementAutomaticallyCreated() {
// test\Helper::HTML does not explicitly define a <body>
	$document = new HTMLDocument(test\Helper::HTML);
	$this->assertInstanceOf("\Gt\Dom\Element", $document->body);
}

// Test live properties:

public function testFormsPropertyWhenNoForms() {
	$documentWithout = new HTMLDocument(test\Helper::HTML);
	$this->assertEquals(0, $documentWithout->forms->length);
}

public function testFormsPropertyWhenForms() {
	$documentWith = new HTMLDocument(test\Helper::HTML_MORE);
	$this->assertEquals(2, $documentWith->forms->length);
}

public function testAnchorsPropertyWhenNoAnchors() {
	$documentWithout = new HTMLDocument(test\Helper::HTML);
	$this->assertEquals(0, $documentWithout->anchors->length);
}

public function testAnchorsPropertyWhenAnchors() {
	$documentWith = new HTMLDocument(test\Helper::HTML_MORE);
// There are actually 3 "a" elements, but only two are anchors - the
// other is a link.
	$this->assertEquals(2, $documentWith->anchors->length);
}

public function testImagesPropertyWhenNoImages() {
	$documentWithout = new HTMLDocument(test\Helper::HTML);
	$this->assertEquals(0, $documentWithout->images->length);
}

public function testImagesPropertyWhenImages() {
	$documentWith = new HTMLDocument(test\Helper::HTML_MORE);
	$this->assertEquals(2, $documentWith->images->length);
}

public function testLinksPropertyWhenNoLinks() {
	$documentWithout = new HTMLDocument(test\Helper::HTML);
	$this->assertEquals(0, $documentWithout->links->length);
}

public function testLinksPropertyWhenLinks() {
	$documentWith = new HTMLDocument(test\Helper::HTML_MORE);
	$this->assertEquals(1, $documentWith->links->length);
}

public function testTitleWhenNoTitle() {
	$document = new HTMLDocument(test\Helper::HTML);
	$this->assertEmpty($document->title);

	$newTitle = "New title";
	$document->title = $newTitle;

	$this->assertEquals($newTitle, $document->title);
	$this->assertEquals(
		$newTitle,
		$document->head->querySelector("title")->textContent
	);
}

public function testOptionalTags() {
	$document = new HTMLDocument(test\Helper::HTML_LESS);
	$this->assertCount(3, $document->head->children);
	$this->assertCount(1, $document->body->children);
}

public function testEmptyHTMLDocument() {
	$document = new HTMLDocument("");
	$nothing = $document->querySelector("div");
	$this->assertNull($nothing);
	$this->assertCount(2, $document->documentElement->children);
	$this->assertNotNull($document->head);
	$this->assertNotNull($document->body);
}

}#