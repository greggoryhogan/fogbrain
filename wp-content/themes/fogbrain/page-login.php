<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-11 col-md-9">
            <p>It looks like you aren&rsquo;t registered or signed in. Don&rsquo;t worry, it&rsquo;s all the same process.</p>
            <p>We don&rsquo;t use passwords here, you&rsquo;ll just forget that too. Enter your email below to get your login code.</p>
            <p>P.S. We&rsquo;re new, check your spam folder.</p>
        </div>
        <div class="col-12 col-md-9">
            <form id="login" class="flex">
                <input type="email" value="" placeholder="email@example.com" id="email" />
                <input type="submit" value="Send" class="big-link" />
            </form>
        </div>
       
    </div>
</div>
<?php get_footer(); ?>