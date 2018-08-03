<template>
  <div class="gridContainer">
    <div class="secPaddLg">
      <div class="importItemsForm clearfix">
        <div class="title">Import</div>
        <div class="clearfix">
          <label for="importItems">Choose File </label>
          <input @change="setFile($event)" type="file" id="importItems" name="importItems" required class="disNone">
          <span class="uploadedFileDisplay grayColor">{{file ?'File Selected': 'no file chosen'}} </span>
          <input @click.prevent="sendFile" type="submit" :value="sending?'Uploading..':'Upload'">
        </div>
      </div>
    </div>
    <transition name="popups" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
            <WrapperPopups v-if="status&&$route.query.popup">
    <UploadedStatus v-if="status&&$route.query.popup=='uploaded'" :status="status"/>
            </WrapperPopups>
    </transition>
  </div>
</template>

<script>
import UploadedStatus from "./UploadedStatus";
import WrapperPopups from "@/wrappers/WrapperPopups";
export default {
  components: {
    UploadedStatus,
    WrapperPopups
  },
  data() {
    return {
      file: null,
      status: null,
      sending:false
    };
  },
  methods: {
    sendFile() {
      this.sending = true;
      this.$store
        .dispatch("import_items", this.file)
        .then(res => {
          this.sending = false;
          this.status = res.data;
          this.$router.push({query:{popup:'uploaded'}})
        })
        .catch(err => {
          console.error(err);
          this.$router.push("/500");
        });
    },
    setFile(e) {
      this.file = e.target.files[0];
    }
  }
};
</script>

<style scoped>
input:disabled
{
  background-color: #3b3b3b;
}
</style>
