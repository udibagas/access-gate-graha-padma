<template>
  <el-card :body-style="{ padding: '0px' }">
    <div slot="header" class="d-flex justify-content-between">
      <h3 class="text-muted mt-2">KAMERA</h3>
      <el-form inline @submit.native.prevent>
        <el-form-item class="mb-0">
          <el-button
            @click="openForm({})"
            type="primary"
            icon="el-icon-plus"
            size="small"
            title="Tambah Kamera"
            >TAMBAH KAMERA</el-button
          >
        </el-form-item>
        <el-form-item class="mb-0">
          <el-input
            v-model="keyword"
            placeholder="Cari"
            prefix-icon="el-icon-search"
            clearable
            size="small"
            @change="
              () => {
                this.pagination.current_page = 1
                fetchData()
              }
            "
          ></el-input>
        </el-form-item>
      </el-form>
    </div>

    <el-table
      height="calc(100vh - 171px)"
      stripe
      v-loading="loading"
      :data="tableData"
      @sort-change="sortChange"
      @filter-change="filterChange"
      :default-sort="{ prop: 'name', order: 'ascending' }"
    >
      <el-table-column
        type="index"
        label="#"
        :index="paginated ? pagination.from : 1"
      ></el-table-column>
      <el-table-column prop="name" label="Name" width="200"></el-table-column>
      <el-table-column prop="url" label="URL"></el-table-column>
      <el-table-column prop="user" label="User" width="200"></el-table-column>
      <el-table-column prop="pass" label="Pass" width="200"></el-table-column>

      <el-table-column
        fixed="right"
        width="60"
        header-align="center"
        align="center"
      >
        <template slot="header">
          <el-button
            type="text"
            icon="el-icon-refresh"
            @click="$emit('refreshData')"
          ></el-button>
        </template>

        <template slot-scope="scope">
          <el-dropdown>
            <span class="el-dropdown-link">
              <i class="el-icon-more"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                icon="el-icon-camera"
                @click.native.prevent="testCamera(scope.row.id)"
                >Test Camera</el-dropdown-item
              >
              <el-dropdown-item
                icon="el-icon-edit"
                @click.native.prevent="openForm(scope.row)"
                >Edit</el-dropdown-item
              >
              <el-dropdown-item
                icon="el-icon-delete"
                @click.native.prevent="deleteData(scope.row.id)"
                >Delete</el-dropdown-item
              >
            </el-dropdown-menu>
          </el-dropdown>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="SNAPSHOT KAMERA" center :visible.sync="showSnapshot">
      <img :src="snapshot" alt="" style="width: 100%" />
    </el-dialog>

    <Pagination
      @current-change="currentChange"
      @size-change="sizeChange"
      :page-sizes="pageSizes"
      :pagination="pagination"
      :paginated="paginated"
      :total="tableData.length"
    />

    <el-dialog
      title="KAMERA"
      :visible.sync="showForm"
      :before-close="closeForm"
      :close-on-click-modal="false"
      width="450px"
    >
      <el-form label-width="120px" label-position="left">
        <el-form-item label="Nama" :error="errors.name?.join(',')">
          <el-input v-model="form.name" placeholder="Nama"></el-input>
        </el-form-item>

        <el-form-item label="URL" :error="errors.url?.join(',')">
          <el-input v-model="form.url" placeholder="URL"></el-input>
        </el-form-item>

        <el-form-item label="User" :error="errors.user?.join(',')">
          <el-input v-model="form.user" placeholder="User"></el-input>
        </el-form-item>

        <el-form-item label="Pass" :error="errors.pass?.join(',')">
          <el-input v-model="form.pass" placeholder="Pass"></el-input>
        </el-form-item>
      </el-form>

      <div slot="footer">
        <el-button icon="el-icon-circle-close" @click="closeForm">
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
  </el-card>
</template>

<script>
import crud from '../mixins/crud'

export default {
  middleware: 'admin',
  mixins: [crud],
  data() {
    return {
      url: '/api/camera',
      showSnapshot: false,
      snapshot: null,
    }
  },

  methods: {
    testCamera(id) {
      this.$axios
        .$get(`/api/camera/test/${id}`)
        .then((data) => {
          this.snapshot = 'data:image/jpeg;base64,' + data.snapshot
          this.showSnapshot = true
        })
        .catch((e) => {
          this.$message({
            message: e.response.data.message,
            type: 'error',
          })
        })
    },
  },
}
</script>
