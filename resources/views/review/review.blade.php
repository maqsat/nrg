<!DOCTYPE html>
<html class="nojs html css_verticalspacer" lang="ru-RU">
<head>

    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta name="generator" content="2018.1.0.386"/>

    <script type="text/javascript">


        // Update the 'nojs'/'js' class on the html node
        document.documentElement.className = document.documentElement.className.replace(/\bnojs\b/g, 'js');

        // Check that all required assets are uploaded and up-to-date
        if(typeof Muse == "undefined") window.Muse = {}; window.Muse.assets = {"required":["museutils.js", "museconfig.js", "jquery.watch.js", "webpro.js", "require.js", "contacts.css"], "outOfDate":[]};
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{--<link media="only screen and (max-width: 370px)" rel="alternate" href="http://nrg-max.com/phone/contacts.html"/>--}}
    <title>{{ __('reviews.review') }} | {{ $review->user->name }}{{ $review->product ? ' oб ' . $review->product->title : '' }}</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/css/site_global.css?crc=3988030232"/>
    <link rel="stylesheet" type="text/css" href="/css/master_______-___.css?crc=3872008088"/>
    <link rel="stylesheet" type="text/css" href="/css/contacts.css?crc=388235224" id="pagesheet"/>
    <!-- IE-only CSS -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="/css/iefonts_contacts.css?crc=293060583"/>
    <![endif]-->
    <!-- Other scripts -->
    <script type="text/javascript">
        var __adobewebfontsappname__ = "muse";
    </script>
    <!-- JS includes -->
    <script src="https://webfonts.creativecloud.com/raleway:n7,n6:all;lato:n4,n9:all;montserrat:n4:all.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    <!--[if lt IE 9]>
    <script src="/scripts/html5shiv.js?crc=4241844378" type="text/javascript"></script>
    <![endif]-->
    <!--custom head HTML-->
    <style> .js body {
            visibility: visible !important;
        }
        .review {
            margin: 25px;
            z-index: 7;
            width: 1159px;
            box-shadow: 0 12px 44px rgba(79,87,90,0.2);
            border-style: solid;
            border-width: 1px;
            border-color: #FFFFFF;
            background-color: transparent;
            position: relative;
        }
        .review .review-content {
            padding: 25px;
            width: 100%;
        }
        .review .review-content h3 {
            font-weight: bold;
            font-size: 150%;
            margin-bottom: 20px;
        }
        .review .review-content .video {
            width: calc(50% - 2px);
            display: inline-block;
        }
        .review .review-content .video iframe {
            max-width: 100%;
            min-width: 100%;
        }
        .review .review-content .description {
            padding: 20px 0;
            width: calc(50% - 22px);
            display: inline-block;
            vertical-align: top;
            margin-left: 20px;
        }
        .review .review-content .toolbar {
            margin-top: 20px;
        }
        .review .review-content .toolbar .like,
        .comments .comment-content .toolbar .comment-like
        {
            color: #34B511;
            cursor: pointer;
        }
        .comments .comment-content .toolbar .comment-like {
            margin-right: 20px;
        }
        .heading {
            margin: 0 25px;
            padding-top: 25px;
            width: 1159px;
            clear: both;
            display: flex;
            font-size: 200%;
            font-weight: bold;
        }
        .content-block {
            margin: 25px;
            padding: 25px;
            z-index: 7;
            width: 1109px;
            box-shadow: 0 12px 44px rgba(79,87,90,0.2);
        }
        .form-block {
            display: flex;
            flex-direction: column;
        }
        .comment-name {
            display: flex;
            align-items: center;
        }
        .comments .comment-name {
            justify-content: space-between;
        }
        .comments .comment-name .actions {
            flex-grow: 1;
            text-align: end;
        }
        .comment-name img {
            width: 64px;
            height: 64px;
            border-radius: 100%;
            margin-right: 20px;
        }
        .comment-name span {
            font-size: 120%;
            font-weight: bold;
        }
        .comment-name .date {
            margin: 0 20px;
        }
        .comment-content {
            width: 100%;
            margin: 20px 0;
        }
        .comment-content > .comment-content {
            margin-left: 40px;
            width: calc(100% - 40px);
            margin-bottom: 0;
        }
        .comment-content .message {
            margin: 20px;
            color: #000;
            font-size: 120%;
            font-style: italic;
        }
        .comment-content .toolbar {
            margin: 0 20px;
        }
        .comment-content .toolbar .add_comment {
            color: gray;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 110%;
            letter-spacing: 2px;
        }
        #message {
            margin: 10px 0;
            padding: 10px 15px;
            border: 1px solid gray;
            width: calc(100% - 32px);
            border-radius: 3px;
        }
        #message:focus {
            border-color: black;
        }
        .form-block .btn-add {
            background-color: #272727;
            width: 300px;
            height: 45px;
            color: white;
            border-radius: 3px;
            cursor: pointer;
            margin-left: auto;
            font-size: 120%;
            text-transform: uppercase;
        }
        .form-block .btn-add:hover {
            background-color: #464747;
        }
        a.comment-edit-pen,
        a.comment-edit-pen:visited
        {
            color: #272727;
        }
        .comment-edit-pen:hover {
            color: #464747;
        }
        @media screen and (max-width: 576px) {
            body {
                min-width: 320px;
            }
            #page {
                width: 320px;
            }
            #u1659 {
                height: 430px;
                width: 100%;
                position: absolute;
            }
            #u4497 {
                display: none;
            }
            #u920-3 {
                margin-left: 0;
                top: 10px;
                left: 10px;
                position: absolute;
            }
            #u920-3 p {
                display: none;
            }
            #u4500-4 {
                top: 0;
                left: auto;
                margin-left: 160px;
                width: 120px;
                font-size: 80%;
                padding: 0 15px;
                margin-top: 20px;
                position: absolute;
            }
            #u10779-4, #u10780-4, #u10781-4, #u10782-4, #u10783-4, #u10784-4, #u10785-4, #u10786-4, #u11625-4 {
                position: absolute;
                margin-left: 0!important;
                transform: translate(-50%, 0);
            }
            #u10780-4 {
                top: 153px;
            }
            #u10779-4 {
                top: 183px;
            }
            #u10781-4 {
                top: 213px;
            }
            #u10782-4 {
                top: 243px;
            }
            #u10783-4 {
                top: 273px;
            }
            #u10784-4 {
                top: 303px;
            }
            #u10785-4 {
                top: 333px;
            }
            #u10786-4 {
                top: 363px;
            }
            #u11625-4 {
                top: 393px;
            }
            #pu15867-4 {
                margin-left: 80px;
                margin-bottom: 200px;
                margin-top: 60px;
            }
            #u9426-4, #u9442-4 {
                width: 270px;
            }
            #ppu15867-4 {
                margin-left: 0;
                margin-right: 0;
            }
            .review {
                width: 270px;
            }
            .review .review-content {
                width: calc(100% - 50px);
            }
            .review .review-content h3 {
                line-height: 1.2;
            }
            .review .review-content .video {
                width: 100%;
            }
            .review .review-content .video iframe {
                height: auto;
            }
            .review .review-content .description {
                width: calc(100% - 40px);
            }
            #u100_align_to_page {
                width: 320px;
                left: 0;
            }
            #u101-4 {
                width: 300px;
                left: 50%;
                transform: translate(-50%, 0);
            }
            .heading {
                width: 270px;
                line-height: 1;
            }
            .content-block {
                width: 220px;
            }
            .form-block .btn-add {
                width: 100%;
            }
            .comment-name {
                flex-direction: column;
            }
            .comment-name > * {
                margin-bottom: 20px!important;
                text-align: center;
            }
            .comment-name img {
                margin-right: 0;
            }
            .comments .comment-content .toolbar .comment-like {
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>

<div class="clearfix" id="page" style="min-height: auto"><!-- group -->
    <div class="clearfix grpelem" id="pu1659"><!-- group -->
        <div class="browser_width" id="u1659-bw">
            <div id="u1659"><!-- simple frame --></div>
        </div>
        <div class="browser_width" id="u4497-bw">
            <div id="u4497"><!-- simple frame --></div>
        </div>
        <a class="nonblock nontext clearfix" id="u920-3" href="/index.html"><!-- content --><p>&nbsp;</p></a>
        <a class="nonblock nontext transition clearfix" id="u4500-4" href="/login" ><!-- content --><p>{{ __('reviews.personal_area') }}</p></a>
        <a class="nonblock nontext transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10779-4" href="/index.html#product"><!-- content --><p>{{ __('reviews.products') }}</p></a>
        <a class="nonblock nontext transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10780-4" href="/index.html"><!-- content --><p>{{ __('reviews.main') }}</p></a>
        <a class="nonblock nontext transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10781-4" href="/about.html"><!-- content --><p>{{ __('reviews.about_company') }}</p></a>
        <a class="nonblock nontext transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10782-4" href="/news.html"><!-- content --><p>{{ __('reviews.news') }}</p></a>
        <a class="nonblock nontext transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10783-4" href="/team.html"><!-- content --><p>{{ __('reviews.our_team') }}</p></a>
        <div class="transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10784-4"><!-- content -->
            <p>{{ __('reviews.gallery') }}</p>
        </div>
        <a class="nonblock nontext transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10785-4" href="/reviews"><!-- content --><p>{{ __('reviews.reviews') }}</p></a>
        <a class="nonblock nontext MuseLinkActive transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u10786-4" href="/contacts.html"><!-- content --><p>{{ __('reviews.contacts') }}</p></a>
        <a class="nonblock nontext transition Paragraph-Center-Aligned-p Light-Background-Links clearfix" id="u11625-4" href="/business.html"><!-- content --><p>{{ __('reviews.business_opportunity') }}</p></a>
    </div>
    <div class="clearfix grpelem" id="ppu15867-4"><!-- column -->
        <div class="clearfix colelem" id="pu15867-4"><!-- group -->
            <a class="nonblock nontext transition clearfix grpelem" id="u15867-4" href="/review/{{ $review->id }}/view"><!-- content --><p>RU</p></a>
            <a class="nonblock nontext transition clearfix grpelem" id="u15866-4" href="/review/{{ $review->id }}/view?lang=kz"><!-- content --><p>Kz</p></a>
            <a class="nonblock nontext transition clearfix grpelem" id="u15872-4" href="/review/{{ $review->id }}/view?lang=en"><!-- content --><p>EN</p></a>
            <div class="grpelem" id="u15884"><!-- simple frame --></div>
        </div>
        <div class="clearfix colelem" id="u9426-4"><!-- content -->
            <p>{{ __('reviews.main') }} / {{ __('reviews.reviews') }} / {{ $review->user->name }}{{ $review->product ? ' oб ' . $review->product->title : '' }}</p>
        </div>
        <div class="clearfix colelem" id="u9442-4"><!-- content -->
            <p>{{ __('reviews.review') }}</p>
        </div>
        <div class="clearfix colelem review" data-review-id="{{ $review->id }}"><!-- group -->
            <div class="shadow clearfix grpelem review-content"><!-- content -->
                {{--<h3>{{ $review->user->name }}{{ $review->product ? ' oб ' . $review->product->title : '' }}</h3>--}}
                @if($review->link_youtube)
                    <div class="video">
                        <?php
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $review->link_youtube, $match);
                        $youtube_id = $match[1];
                        ?>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $youtube_id }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @elseif($review->image)
                    <div class="photo">
                        <img src="{{ Storage::url($review->image) }}" alt="photo">
                    </div>
                @endif
                <div class="description">{!! $review->description !!}</div>
                <div class="toolbar">
                    <span class="like">
                        <i class="{{ $user && $user->review_likes()->where('reviews.id', '=', $review->id)->count() ? 'fas' : 'far' }} fa-heart"></i>
                        <span class="count">{{ $review->user_likes()->count() }}</span>
                    </span>
                </div>
            </div>
        </div>
        @if($user)
        <div class="clearfix heading" id="comment"><!-- content -->
            <p>{{ __('reviews.add_comment') }}</p>
        </div>
        <div class="content-block clearfix colelem add_comment_block"><!-- group -->
            <div class="clearfix grpelem comment-content"><!-- content -->
                <h3 class="comment-name"><img src="{{ env('APP_URL') . '/' . $user->photo }}" alt="photo"><span class="name">{{ $user->name }}</span></h3>
                <form id="add_message" class="form-block" action="{{ route('comment_add', [ 'id' => $review->id ]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_id" value="" />
                    <input type="hidden" name="comment_id" value="" />
                    <label for="message" class="hidden">{{ __('reviews.comment') }}:</label>
                    <textarea name="message" id="message" placeholder="{{ __('reviews.leave_a_comment') }}" required cols="30" rows="5"></textarea>
                    <input type="submit" class="btn-add" value="{{ __('reviews.leave') }}">
                </form>
            </div>
        </div>
        @endif
        <?php $comments = $review->comments()->whereNull('comment_id')->orderBy('created_at', 'DESC')->get() ?>
        @if(count($comments))
        <div class="clearfix heading"><!-- content -->
            <p>{{ __('reviews.comments') }}</p>
        </div>
        <div class="content-block clearfix colelem comments"><!-- group -->
            @foreach($comments as $comment)
            <div class="clearfix grpelem comment-content comment-content-main" data-comment-id="{{ $comment->id }}"><!-- content -->
                <?php $comment_user = $comment->user()->first(); ?>
                <h3 class="comment-name"><img src="{{ env('APP_URL') . '/' . $comment_user->photo }}" alt="photo"><span>{{ $comment_user->name }}</span><span class="date">{{ $comment->updated_at->locale('ru')->isoFormat('Do MMMM YYYY HH:mm:ss') }}</span><span class="actions">@if($user && $user->id === $comment_user->id)<a
                            href="#comment" class="comment-edit-pen" title="Редактировать" data-edit-message="{{ $comment->comment }}"><i class="fas fa-pen"></i></a>@endif</span></h3>
                <div class="message">{{ $comment->comment }}</div>
                @if($user)
                <div class="toolbar">
                    <span class="comment-like">
                        <i class="{{ $user && $user->comment_likes()->where('comments.id', '=', $comment->id)->count() ? 'fas' : 'far' }} fa-heart"></i>
                        <span class="count">{{ $comment->user_likes()->count() }}</span>
                    </span>
                    <a href="#comment" class="add_comment" data-user-name="{{ $comment_user->name }}">{{ __('reviews.to_answer') }}</a>
                </div>
                @endif
                @foreach($comment->comments()->orderBy('created_at', 'ASC')->get() as $comment)
                <div class="clearfix grpelem comment-content" data-comment-id="{{ $comment->id }}"><!-- content -->
                    <?php $comment_user = $comment->user()->first() ?>
                    <h3 class="comment-name"><img src="{{ env('APP_URL') . '/' . $comment_user->photo }}" alt="photo"><span>{{ $comment_user->name }}</span><span class="date">{{ $comment->updated_at->locale('ru')->isoFormat('Do MMMM YYYY HH:mm:ss') }}</span><span class="actions">@if($user && $user->id === $comment_user->id)<a
                                href="#comment" class="comment-edit-pen" title="Редактировать" data-edit-message="{{ $comment->comment }}" data-edit-id="{{ $comment->id }}"><i class="fas fa-pen"></i></a>@endif</span></h3>
                    <div class="message">{{ $comment->comment }}</div>
                    <div class="toolbar">
                        <span class="comment-like">
                            <i class="{{ $user && $user->comment_likes()->where('comments.id', '=', $comment->id)->count() ? 'fas' : 'far' }} fa-heart"></i>
                            <span class="count">{{ $comment->user_likes()->count() }}</span>
                        </span>
                        <a href="#comment" class="add_comment" data-user-name="{{ $comment_user->name }}">{{ __('reviews.to_answer') }}</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endif
        <div class="colelem" id="u9443"><!-- simple frame --></div>
    </div>
    <div class="colelem100"></div>
    <div class="browser_width grpelem" id="u100-bw">
        <div id="u100"><!-- group -->
            <div class="clearfix" id="u100_align_to_page">
                <div class="fadein2 clearfix grpelem" id="u101-4"><!-- content -->
                    <p>© 2020. nrg-max.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@if($user)
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Other scripts -->
    <script type="text/javascript">
        $(function () {
            $('.like').click(function (e) {
                var $this = this;
                $.post('{{ route('reviews_like') }}', { '_token': '{{ csrf_token() }}', 'id': $($this).parents('.review').data('review-id'), 'like': $('.far.fa-heart', $this).length }, function (data) {
                    data = JSON.parse(data);
                    $('.fa-heart', $this).toggleClass('far');
                    $('.fa-heart', $this).toggleClass('fas');
                    $('.count', $this).text(data.count);
                });
            });
            $('.comment-like').click(function (e) {
                var $this = this;
                $.post('{{ route('comments_like') }}', { '_token': '{{ csrf_token() }}', 'id': $($this).parents('.comment-content').data('comment-id'), 'like': $('.far.fa-heart', $this).length }, function (data) {
                    data = JSON.parse(data);
                    $('.fa-heart', $this).toggleClass('far');
                    $('.fa-heart', $this).toggleClass('fas');
                    $('.count', $this).text(data.count);
                });
            });
            $('.add_comment,.comment-edit-pen').click(function (e) {
                e.preventDefault();
                var commentId = $(this).parents('.comment-content-main[data-comment-id]').data('comment-id');
                $('.form-block input[name=comment_id]').val(commentId);
                var message = $(this).data('edit-message');
                if(message) {
                    $('.form-block textarea').val(message);
                    $('.form-block input[name=item_id]').val($(this).data('edit-id'));
                    $('#comment p').text('{{ __('reviews.edit_comment') }}');
                    $('.add_comment_block .btn-add').val('{{ __('reviews.edit') }}');
                } else {
                    $('.form-block textarea').val($(this).data('user-name') + ', ');
                    $('#comment p').text('{{ __('reviews.answer_to_comment') }}');
                    $('.add_comment_block .btn-add').val('{{ __('reviews.to_answer') }}');
                }
                $('html, body').animate({
                    scrollTop: $("#comment").offset().top - 150
                }, 1000);
                $('.form-block textarea').focus();
            });
        });
    </script>
@endif
<!-- Other scripts -->
<script type="text/javascript">
    // Decide whether to suppress missing file error or not based on preference setting
    var suppressMissingFileError = false
</script>
<script type="text/javascript">
    window.Muse.assets.check=function(c){if(!window.Muse.assets.checked){window.Muse.assets.checked=!0;var b={},d=function(a,b){if(window.getComputedStyle){var c=window.getComputedStyle(a,null);return c&&c.getPropertyValue(b)||c&&c[b]||""}if(document.documentElement.currentStyle)return(c=a.currentStyle)&&c[b]||a.style&&a.style[b]||"";return""},a=function(a){if(a.match(/^rgb/))return a=a.replace(/\s+/g,"").match(/([\d\,]+)/gi)[0].split(","),(parseInt(a[0])<<16)+(parseInt(a[1])<<8)+parseInt(a[2]);if(a.match(/^\#/))return parseInt(a.substr(1),
        16);return 0},f=function(f){for(var g=document.getElementsByTagName("link"),j=0;j<g.length;j++)if("text/css"==g[j].type){var l=(g[j].href||"").match(/\/?css\/([\w\-]+\.css)\?crc=(\d+)/);if(!l||!l[1]||!l[2])break;b[l[1]]=l[2]}g=document.createElement("div");g.className="version";g.style.cssText="display:none; width:1px; height:1px;";document.getElementsByTagName("body")[0].appendChild(g);for(j=0;j<Muse.assets.required.length;){var l=Muse.assets.required[j],k=l.match(/([\w\-\.]+)\.(\w+)$/),i=k&&k[1]?
        k[1]:null,k=k&&k[2]?k[2]:null;switch(k.toLowerCase()){case "css":i=i.replace(/\W/gi,"_").replace(/^([^a-z])/gi,"_$1");g.className+=" "+i;i=a(d(g,"color"));k=a(d(g,"backgroundColor"));i!=0||k!=0?(Muse.assets.required.splice(j,1),"undefined"!=typeof b[l]&&(i!=b[l]>>>24||k!=(b[l]&16777215))&&Muse.assets.outOfDate.push(l)):j++;g.className="version";break;case "js":j++;break;default:throw Error("Unsupported file type: "+k);}}c?c().jquery!="1.8.3"&&Muse.assets.outOfDate.push("jquery-1.8.3.min.js"):Muse.assets.required.push("jquery-1.8.3.min.js");
        g.parentNode.removeChild(g);if(Muse.assets.outOfDate.length||Muse.assets.required.length)g="Некоторые файлы на сервере могут отсутствовать или быть некорректными. Очистите кэш-память браузера и повторите попытку. Если проблему не удается устранить, свяжитесь с разработчиками сайта.",f&&Muse.assets.outOfDate.length&&(g+="\nOut of date: "+Muse.assets.outOfDate.join(",")),f&&Muse.assets.required.length&&(g+="\nMissing: "+Muse.assets.required.join(",")),suppressMissingFileError?(g+="\nUse SuppressMissingFileError key in AppPrefs.xml to show missing file error pop up.",console.log(g)):alert(g)};location&&location.search&&location.search.match&&location.search.match(/muse_debug/gi)?
        setTimeout(function(){f(!0)},5E3):f()}};
    var muse_init=function(){require.config({baseUrl:""});require(["jquery","museutils","whatinput","webpro","jquery.watch"],function(c){var $ = c;$(document).ready(function(){try{
        window.Muse.assets.check($);/* body */
        Muse.Utils.transformMarkupToFixBrowserProblemsPreInit();/* body */
        Muse.Utils.prepHyperlinks(true);/* body */
        Muse.Utils.resizeHeight('.browser_width');/* resize height */
        Muse.Utils.requestAnimationFrame(function() { $('body').addClass('initialized'); });/* mark body as initialized */
        Muse.Utils.makeButtonsVisibleAfterSettingMinWidth();/* body */
        Muse.Utils.initWidget('#widgetu9682', ['#bp_infinity'], function(elem) { return new WebPro.Widget.Form(elem, {validationEvent:'submit',errorStateSensitivity:'high',fieldWrapperClass:'fld-grp',formSubmittedClass:'frm-sub-st',formErrorClass:'frm-subm-err-st',formDeliveredClass:'frm-subm-ok-st',notEmptyClass:'non-empty-st',focusClass:'focus-st',invalidClass:'fld-err-st',requiredClass:'fld-err-st',ajaxSubmit:true}); });/* #widgetu9682 */
        Muse.Utils.fullPage('#page');/* 100% height page */
        Muse.Utils.showWidgetsWhenReady();/* body */
        Muse.Utils.transformMarkupToFixBrowserProblems();/* body */
    }catch(b){if(b&&"function"==typeof b.notify?b.notify():Muse.Assert.fail("Error calling selector function: "+b),false)throw b;}})})};

</script>
<!-- RequireJS script -->
{{--<script src="/scripts/require.js?crc=7928878" type="text/javascript" async data-main="/scripts/museconfig.js?crc=4286661555" onload="if (requirejs) requirejs.onError = function(requireType, requireModule) { if (requireType && requireType.toString && requireType.toString().indexOf && 0 <= requireType.toString().indexOf('#scripterror')) window.Muse.assets.check(); }" onerror="window.Muse.assets.check();"></script>--}}
</body>
</html>
