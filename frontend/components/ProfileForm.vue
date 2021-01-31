<template>
	<el-dialog
		title="UPDATE PROFILE"
		:visible.sync="show"
		:before-close="(done) => $emit('close')"
		:close-on-click-modal="false"
		v-loading="loading"
	>
		<el-form label-width="180px" label-position="left">
			<el-form-item label="Nama" :class="{ 'is-error': errors.name }">
				<el-input v-model="form.name" placeholder="Nama"></el-input>
				<div class="el-form-item__error" v-if="errors.name">
					{{ errors.name.join(', ') }}
				</div>
			</el-form-item>

			<el-form-item label="Email" :class="{ 'is-error': errors.email }">
				<el-input v-model="form.email" placeholder="Email"></el-input>
				<div class="el-form-item__error" v-if="errors.email">
					{{ errors.email.join(', ') }}
				</div>
			</el-form-item>

			<el-form-item label="Password" :class="{ 'is-error': errors.password }">
				<el-input
					type="password"
					v-model="form.password"
					placeholder="Password"
				></el-input>

				<div class="el-form-item__error" v-if="errors.password">
					{{ errors.password.join(', ') }}
				</div>
			</el-form-item>

			<el-form-item
				label="Konfirmasi Password"
				:class="{ 'is-error': errors.password_confirmation }"
			>
				<el-input
					type="password"
					v-model="form.password_confirmation"
					placeholder="Konfirmasi Password"
				></el-input>

				<div class="el-form-item__error" v-if="errors.password_confirmation">
					{{ errors.password_confirmation.join(', ') }}
				</div>
			</el-form-item>
		</el-form>

		<div slot="footer">
			<el-button icon="el-icon-circle-close" @click="$emit('close')">
				BATAL
			</el-button>

			<el-button
				type="primary"
				icon="el-icon-success"
				@click="save"
				:loading="loading"
			>
				SIMPAN
			</el-button>
		</div>
	</el-dialog>
</template>

<script>
export default {
  props: ['show'],

  data() {
    return {
      form: JSON.parse(JSON.stringify(this.$auth.user)),
      errors: {},
      loading: false
    }
  },

  methods: {
    save() {
      this.loading = true;
      this.$axios.$put(`/api/user/${this.$auth.user.id}`, this.form).then(r => {
        this.$message({ message: r.message, type: 'success', showClose: true });
        this.$emit('close');
      }).catch(e => {
        this.$message({ message: e.response.data.message, type: 'error', showClose: true });

        if (e.response.status == 422) {
          this.errors = e.response.data.errors;
        }
      }).finally(() => this.loading = false);
    },
  }
}
</script>
