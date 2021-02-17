<template>
	<el-card>
		<div class="text-center mb-3">
			<img src="/logo.png" class="img-center" width="120px;" alt="" />
		</div>
		<h4 class="text-center my-3" style="color: #2ed1a2">
			Gate Akses <br />
			Taman Raflesia Graha Padma
		</h4>
		<el-form style="width: 350px" label-position="top" class="p-3">
			<el-form-item>
				<el-input
					prefix-icon="el-icon-user"
					v-model="form.email"
					placeholder="Username/Email"
				></el-input>
			</el-form-item>

			<el-form-item>
				<el-input
					type="password"
					prefix-icon="el-icon-key"
					v-model="form.password"
					placeholder="Password"
				></el-input>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" style="width: 100%" @click="login"
					>LOGIN</el-button
				>
			</el-form-item>
		</el-form>

		<div class="text-center text-muted">
			&copy; {{ new Date().getFullYear() }} - MitraTeknik
		</div>
	</el-card>
</template>

<script>
export default {
  layout: 'login',

  data() {
    return {
      form: {},
      errors: {}
    }
  },

  methods: {
    login() {
      this.$auth.loginWith('laravelSanctum', {
        data: this.form
      }).then(r => {
        this.$router.push(this.$route.query.redirect || '/')
      }).catch(e => {
        var message = '';

        if (e.response.status == 422) {
          message = e.response.data.errors.email
            ? e.response.data.errors.email[0]
            : e.response.data.errors.password[0];
        } else {
          message = e.response.data.message;
        }

        this.$message({ message: message, type: 'error', })
      })
    }
  }
}
</script>
