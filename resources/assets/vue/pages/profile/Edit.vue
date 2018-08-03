<template>
    <div v-if="!loading">
        <div class="secPaddLg whiteBg">
            <div class="gridContainer">
                <div class="top_userProfile clearfix">
                    <div class="avatar" @mouseover="hoverImage=true" @mouseout="hoverImage=false" >
                        <img :src="hoverImage?'https://itavo.nl/img/placeholder.jpg': form.photo" @click="chooseImage">
                    </div>
                    <input type="file" accept="image/*" ref="imageUpload" @change="selectImage" onclick="" style="display: none" />
                    <div class="content">
                        <div class="info">
                            <div class="name">{{form.firstName}}</div>
                            <div class="other">
                                <span class="suboth0"> {{form.profession||''}} </span> <br/>
                                <span class="suboth1"> {{form.about}} </span>
                            </div>
                        </div>
                    </div>
                </div>
                <button :disabled="btnText==='Saving..'" @click="saveEdits" class="topHeadBtn">{{btnText}}</button>
            </div>
        </div>
        <div class="gridContainer">
            <div class="secPaddLg">
                <div class="myrow clearfix">
                    <div class="mycol-md-4">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd">Name</div>
                            <input v-model="form.firstName" type="text" class="inputEle" required>
                        </div>
                    </div>

                    <div class="mycol-md-4">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd">Edit Email</div>
                            <input v-model="form.email" type="email" class="inputEle" required>
                        </div>
                    </div>

                    <div class="mycol-md-4">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd"> I'm.. </div>
                            <input v-model="form.profession" type="text" class="inputEle" required>
                        </div>
                    </div>

                    <div class="mycol-md-9">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd">About me :</div>
                            <textarea class="inputEle" v-model="form.about" row="4"></textarea>
                        </div>
                    </div>
                    <div class="mycol-md-3">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd">Currency :</div>
                            <select required v-model="form.currency" class="inputEle">
                                <option hidden value="">...</option>
                                <option v-for="curr of currency" :key="curr" :value="curr">{{curr}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mycol-md-4">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd">Your Current Password</div>
                            <input v-model="form.currentPassword" type="password" class="inputEle" required>
                        </div>
                    </div>
                    <div class="mycol-md-4">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd">Edit Password</div>
                            <input v-model="form.password" type="password" class="inputEle" required>
                        </div>
                    </div>
                    <div class="mycol-md-4">
                        <div class="mrgBtmLg">
                            <div class="mrgBtmMd">Re-enter Password</div>
                            <input v-model="form.password2" type="password" class="inputEle" required>
                        </div>
                    </div>
                </div>
                <div v-for="(error,i) in errors" :key="i">
                    <h4 class="errors">
                        {{error}}
                    </h4>
                    <br/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { currency } from "@/pages/retailer/sub/currency";

export default {
  data() {
    return {
      form: {
        firstName: this.$store.getters.user.name,
        lastName: "",
        // userName: "",
        profession: this.$store.getters.user.profession||"sss",
        email: this.$store.getters.user.email,
        currentPassword: "",
        password: "",
        currency: this.$store.getters.user.currency,
        about: this.$store.getters.user.about,
        photo: ""
      },
      btnText: "Save Edits",
      errors: [],
      currency,
      hoverImage:false,
      loading: true
    };
  },
  computed: {
    user() {
      return this.$store.getters.userProfile;
    }
  },
  created() {
    this.load(
      () => {
        this.loading = false;
        this.form = {
          firstName: this.user.fname,
          lastName: this.user.lname,
          // userName: "",
            profession: this.user.profession,
          email: this.user.email,
          currentPassword: "",
          password: "",
          password2: "",
          currency: this.user.currency,
          about: this.user.about,
          photo: this.user.photo
            ? this.user.photo.photo_name
            : "/images/male-user-shadow.png",
          image: false
        };
      },
      err => {
        this.loading = false;
        this.errors = err.response.data.errors;
      }
    );
  },
  methods: {
    saveEdits() {
      this.btnText = "Saving..";
      if (
        this.form.password !== "" &&
        this.form.password !== this.form.password2
      ) {
        this.errors = ["Passwords Not Match"];
        this.btnText = "Save Edits";
        return;
      }
      this.$store
        .dispatch("update_user", this.form)
        .then(() => {
          this.btnText = "Saved";
          if (this.form.password) {
            setTimeout(() => {
              this.$store.dispatch("logout");
              this.$router.push({ query: { popup: "login" } });
            }, 500);
          } else {
            let user = { ...this.$store.getters.user };
            user.name = this.form.firstName;
            user.email = this.form.email;
            user.currency = this.form.currency;
            user.about = this.form.about;
            user.profession = this.form.profession
            this.$store.commit("EDIT_USER", user);

          }
        })
        .catch(err => {
          this.btnText = "Save Edits";
          this.errors = err.response.data.errors;
        });
    },

    load(cb, cb_err) {
      this.$store
        .dispatch("get_user_profile", this.$store.getters.user.userId)
        .then(cb)
        .catch(cb_err);
    },
    chooseImage(event) {
      this.$refs.imageUpload.click();
    },
    selectImage(event) {
      var reader = new FileReader();
      reader.readAsDataURL(event.target.files[0]);
      reader.onloadend = () => {
        this.form.image = reader.result;
      };
      this.form.photo = window.URL.createObjectURL(event.target.files[0]);
    }
  }
};
</script>

<style scoped>
.errors {
  color: red;
}

.avatar img {
  width: 100%;
  height: 100%;
  cursor: pointer;
}
.suboth1 {
  font-size: 1em;
  font-weight: 300;
  color: #555454;
}
.suboth0 {
  font-size: 1.3em;
  font-weight: 900;
  color: #3b3b3b;
}
</style>
