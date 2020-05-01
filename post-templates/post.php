
<article class="post">
  <div class="entry-header cf">
    <h1><a href="single.html"></a><?php the_title(); ?></h1>
    <p class="post-meta">
      <time datetime="2014-01-31" class="post-date" pubdate=""><?php the_time('F jS, Y') ?></time>
      <?php the_tags( '', ' / ' ); ?>
    </p>
  </div>

  <div class="post-thumb">
    <?php the_post_thumbnail(); ?>
  </div>

   <div class="post-content">
    <?php the_content(); ?>
  </div>
  
</article>
