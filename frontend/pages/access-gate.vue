<template>
  <el-card :body-style="{ padding: '0px' }">
    <div slot="header" class="d-flex justify-content-between">
      <h3 class="text-muted mt-2">ACCESS GATE</h3>
      <el-form inline @submit.native.prevent>
        <el-form-item class="mb-0">
          <el-button
            @click="openForm({})"
            type="primary"
            icon="el-icon-plus"
            size="small"
            title="Tambah Gate"
            >TAMBAH GATE</el-button
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
      <el-table-column prop="name" label="Nama"></el-table-column>
      <el-table-column prop="device" label="Device"></el-table-column>

      <ActionColumn
        @refreshData="refreshData"
        @openForm="openForm"
        @deleteData="deleteData"
      />
    </el-table>

    <Pagination
      @current-change="currentChange"
      @size-change="sizeChange"
      :page-sizes="pageSizes"
      :pagination="pagination"
      :paginated="paginated"
      :total="tableData.length"
    />

    <el-dialog
      title="GATE"
      :visible.sync="showForm"
      :before-close="closeForm"
      :close-on-click-modal="false"
      width="450px"
    >
      <el-form label-width="120px" label-position="left">
        <el-form-item label="Name" :error="errors.name?.join(',')">
          <el-input v-model="form.name" placeholder="Name"></el-input>
        </el-form-item>

        <el-form-item label="Device" :error="errors.device?.join(',')">
          <el-input v-model="form.device" placeholder="Device"></el-input>
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
import dropdown from '../mixins/dropdown'

export default {
  middleware: 'admin',
  mixins: [crud, dropdown],

  created() {
    this.getList('/api/camera', 'cameraList')
  },

  data() {
    return {
      url: '/api/accessGate',
    }
  },
}
</script>
