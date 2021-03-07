$(document).ready(function() {
    // 字符替换
    function highLightKeywords(text, words, tag) {
        tag = tag || 'span'; // 默认的标签，如果没有指定，使用span
        var i, len = words.length,
            re;

        // for (i = 0; i < len; i++) {
        //     // 正则匹配所有的文本
        //     re = new RegExp(words[i], 'g');
        //     if (re.test(text)) {
        //         text = text.replace(re, '<' + tag + ' style="color:red">$&</' + tag + '>');
        //     }
        // }

        //匹配整个关键词
        re = new RegExp(words, 'g');

        if (re.test(text)) {
            text = text.replace(re, '<' + tag + ' style="color:red!important;">$&</' + tag + '>');
        }
        return text;


        return text;
    }

    // search 关键词 strhead问题 stranswer回答 strtype类型
    var search = $('.keyword').text()
    for (var i = 0; i < 10; i++) {
        var strhead = $('.list-group-item-heading').eq(i).text()
        var stranswer = $('.list-group-item-text.answer').eq(i).text()
        var strtype = $('.k-type em').eq(i).text()
            // console.log(strtype);

        // 用替换函数替换
        var textre = highLightKeywords(strhead, search)
        $('.list-group-item-heading').eq(i).html(textre)

        var textre = highLightKeywords(stranswer, search)
        $('.answer').eq(i).html(textre)

        var textre = highLightKeywords(strtype, search)
        console.log(textre);

        $('.k-type em').eq(i).html(textre)

    }

});