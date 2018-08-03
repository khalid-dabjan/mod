<template>
  <div>

    <div class="secPaddLg whiteBg textCentered">
      <div class="gridContainer">
        <div class="sectionName">
          <div class="theName">DATA FEED GUIDELINES AND SPECIFICATIONS</div>
        </div>
      </div>
    </div>
    <div class="gridContainer">
      <div class="guidlinesPage myTable">
        <div class="tableContainer">
          <table>
            <tbody>
              <tr>
                <th>ATTRIBUTE</th>
                <th>GUIDELINE</th>
                <th>EXAMPLE</th>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">TITLE</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>The title of the item that you want to list. Title should be descriptive and without promotional text.</td>
                <td>Zara black floral dress</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">BRAND</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>The brand name of that particular item</td>
                <td>Zara</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">DESCRIPTION</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>Item description, no link to your store, minimum 100 characters max 500 characters.</td>
                <td>Black dress with floral theme, perfect for evening and elegant occasion. 70%cotton, 30% viscose. Dry clean only.</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">url</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>The URL of the landing page of this particular item. No tracking parameters (this help users verify that the website is legit and will increase your SEO on Google)</td>
                <td>https://Zara.com/women/dress/blackfloraldress.</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">IMGURL</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>The URL of your product image. Recommended size 500px500px. Preferably images shoot on white background. Images once uploaded will be cropped and processed to meet criteria for collage creation No promotional content</td>
                <td>https://Zara.com/women/dress/blackfloraldress.</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">price</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>The ongoing retail price of this particular item. Only numeric number <br>ISO 4217</td>
                <td>20.00<br>150.00<br>9.99</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">sale price</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>The price of this particular item after the sale is applied.<br>Only numeric number <br>ISO 4217</td>
                <td>20.00<br>150.00<br>9.99</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">CURRENCY</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>The currency used to sell this particular item.<br>3 letter ISO 4217</td>
                <td>USD, GBP, EUR, IDR</td>
              </tr>
              <tr>
                <td>
                  <div class="mrgBtmSm">Size</div>
                  <span class="brandColor">Required</span>
                </td>
                <td>Your products size.<br>If a product is available in multiple size, separate the size listing with a comma.</td>
                <td>2,4,6,8 or S,M,XL,XXL</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>

</style>
