                <!-- Page ends here. -->	
            </div>
        </div>
        
        <br clear='all' /><br />
		
        <div id='footer'>
            <p>Content copyright {$site_name}. <a href='http://kittokittokitto.yasashiisyndicate.org'>KittoKittoKitto</a> by <a href='mailto:owlmanatt@gmail.com'>Owl</a> of the Yasashii Syndicate.</p>
            <p>{if $online_users != 1}{kkkurl link_text="$online_users Users Online" slug='online'}{else}{kkkurl link_text="$online_users User Online" slug='online'}{/if} | {kkkurl link_text='Staff' slug='staff'} | {kkkurl link_text='Terms and Conditions' slug='terms-and-conditions'}</p> 
            <p><a href='http://yasashiisyndicate.org'><img src='{$display_settings.public_dir}/resources/images/yasashii_badge.png' border='0' alt='Yasashii Yoake' /></a></p>
        </div>
    </body>
</html>
