<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">KELOLA MEMBER</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-dropdown type="primary">
						<el-button type="primary" size="mini" icon="el-icon-s-tools">
							AKSI <i class="el-icon-arrow-down el-icon--right"></i>
						</el-button>
						<el-dropdown-menu slot="dropdown">
							<el-dropdown-item
								@click.native.prevent="openForm({})"
								icon="el-icon-plus"
								>Tambah Member</el-dropdown-item
							>
							<el-dropdown-item
								@click.native.prevent="triggerOpenFile"
								icon="el-icon-upload2"
							>
								Upload Data Member
							</el-dropdown-item>
							<!-- TODO -->
							<!-- <el-dropdown-item
								@click.native.prevent="exportData"
								icon="el-icon-printer"
								>Print Data Member</el-dropdown-item
							> -->
							<el-dropdown-item
								@click.native.prevent="exportData"
								icon="el-icon-download"
								>Download Data Member</el-dropdown-item
							>
							<el-dropdown-item
								@click.native.prevent="deleteAll"
								icon="el-icon-delete"
								>Hapus Semua Data Member</el-dropdown-item
							>
						</el-dropdown-menu>
					</el-dropdown>
					<input
						type="file"
						style="display: none"
						id="input-file"
						@change="readFile"
					/>
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

			<el-table-column prop="phone" label="Nomor HP"></el-table-column>

			<el-table-column
				prop="card_number"
				label="Nomor Kartu"
				align="center"
				header-align="center"
			></el-table-column>

			<el-table-column
				prop="plate_number"
				label="Plat Nomor"
				align="center"
				header-align="center"
			></el-table-column>

			<el-table-column
				prop="readable_expired_date"
				label="Masa Berlaku"
				align="center"
				header-align="center"
			></el-table-column>

			<el-table-column
				label="Expired"
				width="100"
				align="center"
				header-align="center"
				column-key="expired"
				:filter-multiple="false"
				:filters="[
					{ text: 'YA', value: 'yes' },
					{ text: 'TIDAK', value: 'no' },
				]"
			>
				<template slot-scope="scope">
					<el-tag
						size="mini"
						:type="scope.row.is_expired ? 'danger' : 'success'"
						effect="dark"
						style="width: 100%"
					>
						{{ scope.row.is_expired ? 'YA' : 'TIDAK' }}
					</el-tag>
				</template>
			</el-table-column>

			<el-table-column
				prop="group_name"
				label="Group"
				align="center"
				header-align="center"
				column-key="group"
				:filter-multiple="false"
				:filters="[
					{ value: '0', text: 'MEMBER' },
					{ value: '1', text: 'ADMIN' },
				]"
			></el-table-column>

			<el-table-column
				prop="status"
				label="Status"
				align="center"
				header-align="center"
				column-key="active"
				:filter-multiple="false"
				:filters="[
					{ value: '0', text: 'TIDAK AKTIF' },
					{ value: '1', text: 'AKTIF' },
				]"
			></el-table-column>

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
			title="MEMBER"
			:visible.sync="showForm"
			:before-close="closeForm"
			:close-on-click-modal="false"
		>
			<el-form label-width="180px" label-position="left">
				<el-form-item label="Nama" :class="{ 'is-error': errors.name }">
					<el-input v-model="form.name" placeholder="Nama"></el-input>
					<div class="el-form-item__error" v-if="errors.name">
						{{ errors.name.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="Nomor HP" :class="{ 'is-error': errors.phone }">
					<el-input v-model="form.phone" placeholder="Nomor HP"></el-input>
					<div class="el-form-item__error" v-if="errors.phone">
						{{ errors.phone.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item
					label="Nomor Kartu"
					:class="{ 'is-error': errors.card_number }"
				>
					<el-input
						v-model="form.card_number"
						placeholder="Nomor Kartu"
					></el-input>
					<div class="el-form-item__error" v-if="errors.card_number">
						{{ errors.card_number.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item
					label="Plat Nomor"
					:class="{ 'is-error': errors.plate_number }"
				>
					<el-input
						v-model="form.plate_number"
						placeholder="Plat Nomor"
					></el-input>
					<div class="el-form-item__error" v-if="errors.plate_number">
						{{ errors.plate_number.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item
					label="Masa Aktif"
					:class="{ 'is-error': errors.expired_date }"
				>
					<el-date-picker
						style="width: 100%"
						v-model="form.expired_date"
						type="date"
						format="dd-MMM-yyyy"
						value-format="yyyy-MM-dd"
						placeholder="Masa Aktif"
					></el-date-picker>

					<div class="el-form-item__error" v-if="errors.expired_date">
						{{ errors.expired_date.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="Group" :class="{ 'is-error': errors.group }">
					<el-select
						v-model="form.group"
						placeholder="Group"
						style="width: 100%"
					>
						<el-option :value="0" label="MEMBER"></el-option>
						<el-option :value="1" label="ADMIN"></el-option>
					</el-select>
					<div class="el-form-item__error" v-if="errors.group">
						{{ errors.group.join(', ') }}
					</div>
				</el-form-item>

				<el-form-item label="Status" :class="{ 'is-error': errors.active }">
					<el-select
						v-model="form.active"
						placeholder="Status"
						style="width: 100%"
					>
						<el-option :value="1" label="AKTIF"></el-option>
						<el-option :value="0" label="TIDAK AKTIF"></el-option>
					</el-select>
					<div class="el-form-item__error" v-if="errors.active">
						{{ errors.active.join(', ') }}
					</div>
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
import crud from '../mixins/crud';
import XLSX from 'xlsx';

export default {
  mixins: [crud],

  data() {
    return {
      url: '/api/member',
      paginated: true
    }
  },

  methods: {
    triggerOpenFile() {
      const f = document.getElementById('input-file');
      f.click();
    },

    deleteAll() {
      this.$confirm('Anda yakin akan menghaus semua data member?', 'Konfirmasi', { type: 'warning' }).then(() => {
        this.loading = true;
        this.$axios.$delete(`${this.url}/deleteAll`).then(r => {
          this.$message({
            message: r.message,
            type: 'success',
            showClose: true
          });
          this.refreshData();
        }).catch(e => {
          this.$message({
            message: e.response.data.message,
            type: 'error',
            showClose: true
          });
        }).finally(() => this.loading = false)
      }).catch(e => console.log(e));
    },

    readFile(oEvent) {
      this.loading = true
			var oFile = oEvent.target.files[0]
			var reader = new FileReader()

			reader.onload = (e) => {
				var data = e.target.result
				data = new Uint8Array(data)
				var workbook = XLSX.read(data, { type: 'array' })
				var result = {}
				// see the result, caution: it works after reader event is done.
				var res = XLSX.utils
					.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]], { header: 1 })
					.filter((r) => !!r[0]) // cuma yg ada datanya

				// remove header
				res.splice(0, 1)

				var dataToImport = res.map((r) => {
					return {
						name: r[1] || '',
						phone: r[2] || '',
						card_number: r[3] || '',
						plate_number: r[4] || '',
						expired_date: r[5] || '',
					}
				})

				console.log('raw data: ', dataToImport.length)
				this.importData(dataToImport)
			}

			reader.readAsArrayBuffer(oFile)
    },

    importData(dataToImport) {
			this.loading = true;
			this.$axios
				.$post("/api/member/import", { rows: dataToImport })
				.then((r) => {
					this.$message({
						message: r.message,
            type: "success",
            showClose: true
					});
					this.pagination.current_page = 1;
					this.fetchData();
				})
				.catch((e) => {
					this.$message({
						message: e.response.data.message,
						type: "error",
						showClose: true,
					});
				})
				.finally(() => {
					this.loading = false;
					document.getElementById("input-file").value = "";
				});
		},
  }
}
</script>
