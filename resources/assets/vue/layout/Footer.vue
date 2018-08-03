<template>
    <footer id="footer">
        <div class="gridContainer clearfix">
            <div class="logoFooter">
                <div class="theLogo"><img src="/images/logo.png" alt="modisti"></div>
                <p>A place where women from all over the world meet the widest selection of modest clothing, a place where designers, stylists, influencers, and shoppers meet and inspire each other with new fashion and trends. Indeed the era of modest fashion has just begun.</p>
                <div class="social">
                    <a href="https://www.facebook.com/modestfashionforall/">
                        <i class="fa fa-facebook-official"></i>
                    </a>
                    <a href="https://www.instagram.com/modasti_modestfashionforall/">
                        <i class="fa fa-instagram"></i>
                    </a>
                </div>
            </div>
            <div class="menu">
                <div class="footerTitle">QUICK LINKS</div>
                <ul>
                    <li>
                        <a href="#">Privacy Policy</a>
                        <!-- <li>
						<a href="#">DATA FEED GUIDELINES AND SPECIFICATIONS</a>
					</li> -->

                    </li>
                    <li>
                        <router-link to="/about">About us </router-link>
                    </li>
                    <li>
                        <router-link to="/contact">CONTACT US</router-link>
                    </li>
                    <li>
                        <router-link to="/terms">Terms of service</router-link>
                    </li>
                    <li>
                        <router-link to="/business-with-us">Business with us</router-link>
                    </li>
                    <li>
                        <router-link to="/help">Help</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</template>

<style>
#footer .menu {
  float: right;
}
</style>