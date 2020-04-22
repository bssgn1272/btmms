$(function () {
        $('#js-news').ticker({
            htmlFeed: false,
            ajaxFeed: true,
            feedUrl: 'http://news.google.com/news?ned=us&topic=h&output=rss',
            feedType: 'xml'
        });
    });