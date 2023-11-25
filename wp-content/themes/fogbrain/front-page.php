<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-9 col-xs-8 col-md-9">
            <p>Let&rsquo;s face it, math is hard and we&rsquo;re getting old. Save the dates you keep forgetting and let computers do the work for you.</p>
        </div>
        <div class="col-10 col-xs-10 col-md-9">
            <p>Here&rsquo;s some reminders I&rsquo;ve saved&nbsp;-<p>
        </div>
        <div class="col-12 col-md-9 col-lg-8">
            <div class="reminder-summary">
                <div class="reminders">
                    <div class="reminder">
                    <?php 
                    $reminder = array(
                        'date' => 'September 10, 2021',
                        'is_birthday' => false,
                        'primary_subject' => '',
                        'about_me' => true,
                        'phrase' => 'I have been married',
                        'tense' => 'past perfect continuous',
                        'complement' => '',
                        'note' => 'Vegas, baby!',
                        'public' => true,
                        'tag' => 'Accomplishments'
                    );
                    echo process_gpt_reminder($reminder);
                    ?>
                    </div>
                    <div class="reminder">
                    <?php 
                    $reminder = array(
                        'date' => 'November 17, 2017',
                        'is_birthday' => true,
                        'primary_subject' => 'My dog, Harvey',
                        'about_me' => false,
                        'phrase' => 'My dog, Harvey, is',
                        'tense' => 'past perfect continuous',
                        'complement' => '',
                        'note' => '',
                        'public' => true,
                        'tag' => 'Birthdays'
                    );
                    echo process_gpt_reminder($reminder);
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-9">
            <p>You can use Fog Brain as your personal notepad, or choose to share your reminders with friends and family. Decide which reminders are public and which ones are for your eyes only. How you use Fog Brain is entirely up to you.</p>
        </div>
        <div class="col-12 col-md-9">
            <p>Want to add your own dates to remember?</p>
            <p><a href="/login" class="big-link has-arrow homepage-conditional-login-link" title="Log in or register">Log in or register</a></p>
        </div>
        <!--<div class="col-11 col-md-9">
            <p>It looks like you aren&rsquo;t registered or signed in. We don&rsquo;t use passwords here, you&rsquo;ll just forget that too, so enter your email below to get a passcode to login or register. P.S. We&rsquo;re new, check your spam folder.</p>
        </div>
        <div class="col-12 col-md-9">
            <form id="login" class="flex">
                <input type="email" value="" placeholder="email@example.com" id="email" />
                <input type="submit" value="Send" class="big-link" />
            </form>
        </div>-->
    </div>
</div>
<?php get_footer(); ?>