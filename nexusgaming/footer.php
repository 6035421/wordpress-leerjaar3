</div>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-branding">
                    <div class="footer-logo">
                        <img src="http://localhost/wordpress/wp-content/uploads/2026/02/cropped-1770371246551-Photoroom.png" alt="Nexus Gaming Logo" width="40" height="40">
                    </div>
                    <div class="footer-brand-text">
                        <h3>Nexus Gaming</h3>
                        <p>Gaming community</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-center">
                <div class="footer-info">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('Alle rechten voorbehouden.', 'nexusgaming'); ?></p>
                    <p><?php _e('Gemaakt met', 'nexusgaming'); ?> <a href="<?php echo esc_url('https://wordpress.org/'); ?>" target="_blank"><?php _e('WordPress', 'nexusgaming'); ?></a> <?php _e('en het Nexus Gaming Theme', 'nexusgaming'); ?>.</p>
                </div>
            </div>
            
            <div class="footer-right">
    <div class="footer-menu-columns">
        <div class="footer-menu-column">
            <a href="<?php echo esc_url(nexusgaming_custom_url('blog/')); ?>"><?php _e('Blog', 'nexusgaming'); ?></a>
            <a href="<?php echo esc_url(nexusgaming_custom_url('about/')); ?>"><?php _e('About', 'nexusgaming'); ?></a>
            <a href="<?php echo esc_url(nexusgaming_custom_url('faqs/')); ?>"><?php _e('FAQs', 'nexusgaming'); ?></a>
            <a href="<?php echo esc_url(nexusgaming_custom_url('authors/')); ?>"><?php _e('Authors', 'nexusgaming'); ?></a>
        </div>
        <div class="footer-menu-column">
            <a href="<?php echo esc_url(nexusgaming_custom_url('events/')); ?>"><?php _e('Events', 'nexusgaming'); ?></a>
            <a href="<?php echo esc_url(nexusgaming_custom_url('shop/')); ?>"><?php _e('Shop', 'nexusgaming'); ?></a>
            <a href="<?php echo esc_url(nexusgaming_custom_url('patterns/')); ?>"><?php _e('Patterns', 'nexusgaming'); ?></a>
            <a href="<?php echo esc_url(nexusgaming_custom_url('themes/')); ?>"><?php _e('Themes', 'nexusgaming'); ?></a>
        </div>
    </div>
</div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>