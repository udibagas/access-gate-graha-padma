<template>
  <el-dialog
    title="UPDATE PROFILE"
    :visible.sync="show"
    :before-close="(done) => $emit('close')"
    :close-on-click-modal="false"
    v-loading="loading"
    width="600px"
  >
    <el-form label-width="185px" label-position="left">
      <el-form-item label="Nama" :error="errors.name?.join(',')">
        <el-input v-model="form.name" placeholder="Nama"></el-input>
      </el-form-item>

      <el-form-item label="Email" :error="errors.email?.join(',')">
        <el-input v-model="form.email" placeholder="Email"></el-input>
      </el-form-item>

      <el-form-item label="Password" :error="errors.password?.join(',')">
        <el-input
          type="password"
          v-model="form.password"
          placeholder="Password"
        ></el-input>
      </el-form-item>

      <el-form-item
        label="Konfirmasi Password"
        :error="errors.password_confirmation?.join(',')"
      >
        <el-input
          type="password"
          v-model="form.password_confirmation"
          placeholder="Konfirmasi Password"
        ></el-input>
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
      loading: false,
    }
  },

  methods: {
    save() {
      this.loading = true
      this.$axios
        .$put(`/api/user/${this.$auth.user.id}`, this.form)
        .then((r) => {
          this.$message({
            message: r.message,
            type: 'success',
            showClose: true,
          })
          this.$emit('close')
        })
        .catch((e) => {
          this.$message({
            message: e.response.data.message,
            type: 'error',
            showClose: true,
          })

          if (e.response.status == 422) {
            this.errors = e.response.data.errors
          }
        })
        .finally(() => (this.loading = false))
    },
  },
}
</script>
