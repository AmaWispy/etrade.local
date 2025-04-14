@php
    $type = in_array(request()->route()->getName(), ['shop.card']) ? 'product' :  'blog';
    $page_id = '';
    if(isset($product)){
        $page_id = '/' . $product->id;
    }
@endphp
<h1 class="text-xl font-semibold mb-4" id="total_comments">{{ $comments->total() . ' ' . __('template.comments') }}</h1>
<div class="flex flex-col gap-4" id="comments-block">
    @if ($comments->isNotEmpty())
        @foreach ($comments as $comment )
            @if ($comment->reply_id === null) 
                <div class="flex flex-col gap-4" x-data='{see_replys: {{ count($comment->replies) > 1 ? "false" : "true" }} }'>
                    <!-- Main Coment Start -->
                        <div class="flex gap-3.5">
                            <div class="lg:h-16 lg:w-16 md:w-12 md:h-12 h-10 w-10 rounded-full overflow-hidden">
                                <img src="{{ asset('template/images/user-template.png') }}" class="h-full w-full object-cover" alt="Cameron Williamson">
                            </div>
                            <div class="w-4/5">
                                <ul class="flex flex-col gap-1">
                                    <li class="flex items-center justify-between w-full">
                                        <h1 class="lg:text-lg text-base font-semibold">{{ $comment->user->name }}</h1>
                                        @if ($comment->rating !== null)
                                            <div class="flex gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="bi {{ $i <= $comment->rating ? 'bi-star-fill text-yellow-400' : 'bi-star' }}"></i>
                                                @endfor
                                            </div>
                                        @endif
                                    </li>
                                    <li class="flex gap-2 lg:text-base text-sm text-neutral-500">
                                        <p>{{ $comment->formattedDate() }}</p>
                                        <button
                                            type="button" 
                                            @click="comment_id = {{ $comment->id }}; user_name = '{{ $comment->user->name }}'"
                                            class="text-blue-500 xl:hover:text-blue-600 font-semibold reply_btn">{{ __('template.reply') }}</button>
                                    </li>
                                    <li>
                                        <p class="lg:text-base text-sm">{{ $comment->content }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <!-- Main Coment End -->

                    <!-- Reply Start -->
                        <div class="overflow-hidden flex flex-col gap-2">
                            <div x-show='see_replys'>
                                @foreach ($comment->replies as $replies)
                                    <div class="md:pl-16 pl-5 mb-3 flex flex-col gap-4">
                                        <div class="flex gap-3.5">
                                            <div class="lg:h-16 lg:w-16 md:w-12 md:h-12 h-10 w-10 rounded-full overflow-hidden">
                                                <img src="{{ asset('template/images/user-template.png') }}" class="h-full w-full object-cover" alt="Cameron Williamson">
                                            </div>
                                            <div class="w-4/5">
                                                <ul class="flex flex-col gap-1">
                                                    <li class="flex items-center gap-2">
                                                        <h1 class="lg:text-lg text-base font-semibold">{{ $replies->user->name }}</h1>
                                                        @if (isset($replies->replyUser) && $replies->user_id !== $replies->reply_user_id)
                                                            <span class="flex items-center text-sm border rounded-xl text-white bg-neutral-500 px-2 py-[3px] gap-1"><i class="bi bi-reply-all"></i> {{ $replies->replyUser->name }}</span>
                                                        @endif
                                                    </li>
                                                    <li class="flex gap-2 lg:text-base text-sm text-neutral-500">
                                                        <p>{{ $replies->formattedDate() }}</p>
                                                        <button
                                                            type="button" 
                                                            @click="comment_id = {{ $comment->id }}; user_name = '{{ $comment->user->name }}'; comment_reply_user_id = {{ $replies->user_id }};"
                                                            class="text-blue-500 xl:hover:text-blue-600 font-semibold reply_btn">{{ __('template.reply') }}</button>
                                                    </li>
                                                    <li>
                                                        <p class="lg:text-base text-sm">{{ $replies->content }}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($comment->replies !== null && count($comment->replies) > 1)
                                <button type="button" class="text-lg flex items-center justify-center gap-1.5" x-on:click='see_replys = !see_replys'>{{ __('template.view_answers') . ' (' . count($comment->replies) . ')' }}
                                    <span class="duration-300" x-bind:class=' see_replys ? "rotate-180" : "rotate-0" '>
                                        <i class="bi bi-chevron-compact-up"></i>
                                    </span>
                                </button>
                            @endif
                        </div> 
                    <!-- Reply End -->
                </div>
            @endif
        @endforeach
    @else
        <p class="text-center text-xl font-semibold text-neutral-500 my-14">{{ __('template.empty') }}...</p>
    @endif
</div>
<div class="flex justify-center items-center py-2 mt-5 border rounded-lg 2xl:text-xl xl:text-xl lg:text-lg md:text-base text-sm  font-medium  xl:hover:text-white xl:hover:bg-blue-500 text-secondary-400">
    <button type="button" id="load-comments" class="w-full h-full">{{ __('template.show_more') }}...</button>
    <button type="button" id="hidden-comments" class="w-full h-full hidden">{{ __('template.hide') }}</button>
</div>

<script type="module">
    /**
     * COMMENTS SECTION
     */
        function initScrollReply(){
            $('.reply_btn').on('click', function () {
                $('html, body').animate({
                    scrollTop: $('#form_send_comment').offset().top
                });
            });
        }
        initScrollReply()
    /**
     * Load other coments 
     */
    let currentPage = 1,
        totalPage = @json($comments->lastPage());

    const slugPage = @json($slug);

    $('#hidden-comments').on('click', function(){
        const $button = $(this);
        $button.addClass('hidden');

        currentPage = 1;
        $('#load-comments').removeClass('hidden').addClass('block');
        loadComments(currentPage )
        
        $('#comments-block').html('')
    })

    $('#load-comments').on('click', function(){
        const $button = $(this);
        if(currentPage < totalPage){
            currentPage ++;

            loadComments(currentPage)
            
        } else if(currentPage === totalPage){
            $button.addClass('hidden');
            $('#hidden-comments').removeClass('hidden').addClass('block');
        }
    })
    

    /**
     * Send Comment
     */
    const skeleton = `
        <div role="status" class="w-full border-gray-200 rounded-sm flex items-center gap-3 animate-pulse  dark:border-gray-700">
            <svg class="lg:h-16 lg:w-16 md:w-12 md:h-12 h-10 w-10 me text-gray-200 dark:text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                </svg>
                <div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-2"></div>
                    <div class="w-48 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                    <div class="w-48 h-2 mt-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                </div>
            <span class="sr-only">Loading...</span>
        </div>
    `;
    
    $('#send-btn').on('click', function (e) {
        e.preventDefault()
        const $button = $(this);
        // $button.prop('disabled', true); // Disable the button
        let message = $('#message').val(),
            comment_id = $('#comment_id').val(),
            comment_reply_user_id = $('#comment_reply_user_id').val(),
            rating = $('#rating').val() || null,
            data = {
                message: message,
                comment_id: comment_id,
                comment_reply_user_id: comment_reply_user_id,
                type: @json($type ),
                blog_id: @json($post->id ?? null),
                product_id: @json($product->id ?? null),
                rating: rating,
            };

        $.ajax({
            url: `/send-comment`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: data,
            beforeSend: function () {
                $('#message').val(''); // Clear the textarea
                $('#comments-block').html(''); 
                console.log( rating)
                for (let i = 0; i < 4; i++) {
                    $('#comments-block').append(skeleton); 
                    
                }
            },
            success: function (response) {
                if(response.status === 200){
                    $('#comments-block').html(''); 
                    loadComments()
                }
                $button.prop('disabled', false); // Disable the button
            },

            error:function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
            }
        })
    })

    function loadComments(currentPage = 1 ){
        const type = @json($type);
        const pageId = @json($page_id);
        $.ajax({
            url: `/${type}/${slugPage}${pageId}?page=${currentPage}&type=${type}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            
            success: function (response) {
                if(response.status === 200){
                    $('#total_comments').text('')
                    $('#rating_product_stars').html('')

                    $('#qnty_rating_users').text(response.ratingQntyUsers)
                    $('#total_comments').text(response.comments.total)

                    currentPage = response.comments.page.current_page;
                    totalPage = response.comments.page.total_page;

                    for (let i = 0; i < 5; i++) {
                        $('#rating_product_stars').append(`
                            <i class="bi bi-star${i < Math.floor(response.rating) ? '-fill' : ''} text-yellow-400"></i>
                        `)
                    }

                    commentsAppend(response.comments.comments, '#comments-block')
                    initScrollReply()
                }

            },

            error:function(xhr, status, error) {
                console.error('Error:', xhr, status, error);
            }
        })
    }

    function commentsAppend(comments, name_block){
        console.log(comments)

        comments.forEach(comment => {
        if (comment.reply_id === null){
            let commentBlock = `<div class="flex flex-col gap-4" x-data='{see_replys: ${comment.comment_replies.length > 1 ? false : true} }'>
                <!-- Main Comment Start -->
                <div class="flex gap-3.5">
                    <div class="lg:h-16 lg:w-16 md:w-12 md:h-12 h-10 w-10 rounded-full overflow-hidden">
                        <img src="{{ asset('template/images/user-template.png') }}" class="h-full w-full object-cover" alt="${comment.comment_user.name}">
                    </div>
                    <div class="w-4/5">
                        <ul class="flex flex-col gap-1">
                            <li class="flex items-center justify-between w-full">
                                <h1 class="lg:text-lg text-base font-semibold">${comment.comment_user.name}</h1>`
                if(comment.rating !== null){
                    commentBlock += ' <div class="flex gap-1">'
                        for (let i = 1; i <= 5 ; i++) {
                            commentBlock += `<i class="bi ${i <= comment.rating ? 'bi-star-fill text-yellow-400' : 'bi-star'}"></i>`
                        }
                    commentBlock += '</div>'
                }
                        
                commentBlock +=  `</li>
                            <li class="flex gap-2 lg:text-base text-sm text-neutral-500">
                                <p>${comment.created_at}</p>
                                <button
                                    type="button" 
                                    @click="comment_id = ${comment.id}; user_name = '${comment.comment_user.name}'"
                                    class="text-blue-500 xl:hover:text-blue-600 font-semibold reply_btn">{{ __('template.reply') }}</button>
                            </li>
                            <li>
                                <p class="lg:text-base text-sm">${comment.content}</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Main Comment End -->

                <!-- Reply Start -->
                <div class="overflow-hidden flex flex-col gap-2">
                    <div x-show='see_replys'>`;

            comment.comment_replies.forEach(reply => {
                commentBlock += `
                    <div class="md:pl-16 pl-5 mb-3 flex flex-col gap-4">
                        <div class="flex gap-3.5">
                            <div class="lg:h-16 lg:w-16 md:w-12 md:h-12 h-10 w-10 rounded-full overflow-hidden">
                                <img src="{{ asset('template/images/user-template.png') }}" class="h-full w-full object-cover" alt="${reply.comment_reply_user.name}">
                            </div>
                            <div class="w-4/5">
                                <ul class="flex flex-col gap-1">
                                    <li class="flex items-center gap-2">
                                        <h1 class="lg:text-lg text-base font-semibold">${reply.comment_reply_user.name}</h1>`;
                                        
                                        if (reply.reply_user && reply.reply_user_id !== reply.comment_reply_user.id) {
                                            commentBlock += `
                                            <span class="flex items-center text-sm border rounded-xl text-white bg-neutral-500 px-2 py-[3px] gap-1">
                                                <i class="bi bi-reply-all"></i> ${reply.reply_user.name}
                                            </span>`;
                                        }
                                        
                commentBlock += `
                                    </li>
                                    <li class="flex gap-2 lg:text-base text-sm text-neutral-500">
                                        <p>${reply.reply.created_at}</p>
                                        <button
                                            type="button" 
                                            @click="comment_id = ${comment.id}; user_name = '${comment.comment_user.name}'; comment_reply_user_id = ${reply.comment_reply_user.id }"
                                            class="text-blue-500 xl:hover:text-blue-600 font-semibold reply_btn">{{ __('template.reply') }}</button>
                                    </li>
                                    <li>
                                        <p class="lg:text-base text-sm">${reply.reply.content}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>`;
            });

            commentBlock += `</div>`;

            // **Кнопка "Показать ответы", если больше 1 ответа**
            if (comment.comment_replies.length > 1) {
                commentBlock += `
                    <button type="button" class="text-lg flex items-center justify-center gap-1.5" x-on:click='see_replys = !see_replys'>{{ __('template.view_answers') }}  ${comment.comment_replies.length}
                        <span class="duration-300" x-bind:class=' see_replys ? "rotate-180" : "rotate-0" '>
                            <i class="bi bi-chevron-compact-up"></i>
                        </span>
                    </button>`;
            }

            commentBlock += `</div> <!-- Reply End -->
            </div>`;
            $(name_block).append(commentBlock);
        };

    });
}
</script>