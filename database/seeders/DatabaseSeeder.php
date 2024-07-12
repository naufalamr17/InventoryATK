<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory()->create([
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'status' => 'Administrator',
            'location' => 'Head Office',
            'password' => ('12345678')
        ]);

        DB::table('employees')->insert([
            ['NIK' => 'MLP 001.0322.2021', 'NAMA' => 'Chris Januardi Lim', 'AREA' => 'Head Office', 'DEPT' => 'Admin & General', 'JABATAN' => 'Deputy GM Operation'],
            ['NIK' => 'MLP 001.0009.2017', 'NAMA' => 'Andi Sari Dewi', 'AREA' => 'Head Office', 'DEPT' => 'Admin & General', 'JABATAN' => 'Deputy GM Support'],
            ['NIK' => 'MLP 001.0064.2018', 'NAMA' => 'Hendy Darius Gunawan', 'AREA' => 'Head Office', 'DEPT' => 'Admin & General', 'JABATAN' => 'Director Operation'],
            ['NIK' => 'MLP 001.0431.2023', 'NAMA' => 'Oky Saputra', 'AREA' => 'Head Office', 'DEPT' => 'Admin & General', 'JABATAN' => 'General Manager Operasional'],
            ['NIK' => 'MLP 002.0261.2020', 'NAMA' => 'Mutiara Dinda', 'AREA' => 'Head Office', 'DEPT' => 'Admin & General', 'JABATAN' => 'Personal Assistant'],
            ['NIK' => 'MLP 001.0001.2014', 'NAMA' => 'Gregorius Nong', 'AREA' => 'Head Office', 'DEPT' => 'Admin & General', 'JABATAN' => 'President Director'],
            ['NIK' => 'MLP 002.0438.2023', 'NAMA' => 'Fira Pratiwi Darsono', 'AREA' => 'Head Office', 'DEPT' => 'Business Analyst Department', 'JABATAN' => 'Staff Data Analyst'],
            ['NIK' => 'MLP 002.0489.2023', 'NAMA' => 'Sherina Anugerah Putri', 'AREA' => 'Head Office', 'DEPT' => 'Business Analyst Department', 'JABATAN' => 'Staff Data Analyst'],
            ['NIK' => 'MLP 002.0513.2023', 'NAMA' => 'Emiliana Bella Justisia', 'AREA' => 'Head Office', 'DEPT' => 'Business Analyst Department', 'JABATAN' => 'Staff Data Analyst'],
            ['NIK' => 'MLP 002.0385.2022', 'NAMA' => 'Wildan Saefudin', 'AREA' => 'Head Office', 'DEPT' => 'Business Analyst Department', 'JABATAN' => 'Supervisor Data Analyst'],
            ['NIK' => 'MLP 001.0536.2023', 'NAMA' => 'Reza Raharja', 'AREA' => 'Head Office', 'DEPT' => 'Engineering Department', 'JABATAN' => 'Manager Engineer'],
            ['NIK' => 'MLP 002.0529.2023', 'NAMA' => 'Aji Lambang Hidayah', 'AREA' => 'Head Office', 'DEPT' => 'Engineering Department', 'JABATAN' => 'Staff Mine Plan Engineer'],
            ['NIK' => 'MLP 002.0445.2023', 'NAMA' => 'Muhammad Arief Burhanuddin', 'AREA' => 'Head Office', 'DEPT' => 'Engineering Department', 'JABATAN' => 'Supervisor Mine Engineering'],
            ['NIK' => 'MLP 002.0378.2022', 'NAMA' => 'Patrick Jestino Hosea Nayoan', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Admin Accounting'],
            ['NIK' => 'MLP 002.0303.2021', 'NAMA' => 'Wenty Anggraini Octavia', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Assistant Manager Finance Accounting & Tax'],
            ['NIK' => 'MLP 002.0330.2021', 'NAMA' => 'Yuniar Melania', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Staff Accounting'],
            ['NIK' => 'MLP 002.0493.2023', 'NAMA' => 'Rafi\'i Habib Al Rasyid', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Staff Accounting'],
            ['NIK' => 'MLP 002.0159.2019', 'NAMA' => 'Dinda Syifa Lestari', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Staff Finance'],
            ['NIK' => 'MLP 002.0382.2022', 'NAMA' => 'Sakinah Suwardin', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Staff Finance'],
            ['NIK' => 'MLP 002.0586.2023', 'NAMA' => 'Nela Anggraeni', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Staff Finance'],
            ['NIK' => 'MLP 002.0419.2022', 'NAMA' => 'Rebecca Felicia Hutapea', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Staff Tax'],
            ['NIK' => 'MLP 002.0089.2018', 'NAMA' => 'Citra Meiliani', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Supervisor Finance'],
            ['NIK' => 'MLP 002.0420.2022', 'NAMA' => 'Rizka Rahmawati', 'AREA' => 'Head Office', 'DEPT' => 'Finance Accounting Tax Department', 'JABATAN' => 'Supervisor Tax'],
            ['NIK' => 'MLP 002.0527.2023', 'NAMA' => 'Farah Anne Riyana', 'AREA' => 'Head Office', 'DEPT' => 'Geology & Quality Department', 'JABATAN' => 'Superintendent Geology & Quality'],
            ['NIK' => 'MLP 001.0406.2022', 'NAMA' => 'Huzaini Sahib', 'AREA' => 'Head Office', 'DEPT' => 'Government & Relation Department', 'JABATAN' => 'Government Relation & License Specialist'],
            ['NIK' => 'MLP 002.0404.2022', 'NAMA' => 'Ni Komang Arya Diah Tri', 'AREA' => 'Head Office', 'DEPT' => 'Government & Relation Department', 'JABATAN' => 'Staff Government & Relation'],
            ['NIK' => 'MLP 002.0014.2017', 'NAMA' => 'Maryati', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Cleaning Service'],
            ['NIK' => 'MLP 002.0313.2021', 'NAMA' => 'Dedy Syarifudin', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Driver'],
            ['NIK' => 'MLP 002.0383.2022', 'NAMA' => 'Adi Saputra Antonius Gultom', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Driver'],
            ['NIK' => 'MLP 002.0388.2022', 'NAMA' => 'Widianto', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Driver'],
            ['NIK' => 'MLP 002.0185.2019', 'NAMA' => 'Agung Rohmansyah', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Messenger'],
            ['NIK' => 'MLP 002.0511.2023', 'NAMA' => 'Reggie Indah Fauziah Hendriyati', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Staff GA & Recepsionist'],
            ['NIK' => 'MLP 002.0183.2019', 'NAMA' => 'Lutfi Yasinta', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Staff HRGA'],
            ['NIK' => 'MLP 002.0440.2023', 'NAMA' => 'Anindya Caesara', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Supervisor Compensation & Benefit'],
            ['NIK' => 'MLP 002.0276.2021', 'NAMA' => 'Andhika Aditya', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Supervisor HR'],
            ['NIK' => 'MLP 002.0386.2022', 'NAMA' => 'Muhamad Sayadih', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Supervisor IT'],
            ['NIK' => 'MLP 002.0510.2023', 'NAMA' => 'Tinezia Yemima Adeningsih', 'AREA' => 'Head Office', 'DEPT' => 'Legal Department', 'JABATAN' => 'Staff Legal'],
            ['NIK' => 'MLP 002.0138.2018', 'NAMA' => 'Petrus Antonius Jehadu', 'AREA' => 'Head Office', 'DEPT' => 'Legal Department', 'JABATAN' => 'Supervisor Corporate Legal'],
            ['NIK' => 'MLP 002.0396.2022', 'NAMA' => 'Siti Fadillah Zulmenawati', 'AREA' => 'Head Office', 'DEPT' => 'Supply Chain Management Department', 'JABATAN' => 'Staff Procurement'],
            ['NIK' => 'MLP 002.0426.2023', 'NAMA' => 'Bowo Kerta Saputra', 'AREA' => 'Head Office', 'DEPT' => 'Supply Chain Management Department', 'JABATAN' => 'Staff Supply Chain'],
            ['NIK' => 'MLP 002.0530.2023', 'NAMA' => 'Tsamaratul Fuad', 'AREA' => 'Head Office', 'DEPT' => 'Supply Chain Management Department', 'JABATAN' => 'Staff Supply Chain'],
            ['NIK' => 'MLP 002.0391.2022', 'NAMA' => 'Victor Samuel Sianipar', 'AREA' => 'Head Office', 'DEPT' => 'Supply Chain Management Department', 'JABATAN' => 'Supervisor SCM'],
            ['NIK' => 'MLP 001.0609.2024', 'NAMA' => 'Bambang Eko Widodo', 'AREA' => 'Head Office', 'DEPT' => 'Geology & Quality', 'JABATAN' => 'Manager Geology & Quality'],
            ['NIK' => 'MLP 002.0629.2024', 'NAMA' => 'Tri Apri Nurcahyo', 'AREA' => 'Head Office', 'DEPT' => 'Geology & Quality', 'JABATAN' => 'Foreman Geology Eksplorasi'],
            ['NIK' => 'MLP 002.0447.2023', 'NAMA' => 'Eko Wahono', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Driver'],
            ['NIK' => 'MLP 002.0652.2024', 'NAMA' => 'Nurbaya Sangaji', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Receptionist'],
            ['NIK' => 'MLP 002.0651.2024', 'NAMA' => 'Triana Ayu Susanti', 'AREA' => 'Head Office', 'DEPT' => 'Business Analyst Department', 'JABATAN' => 'Supervisor Data Analyst'],
            ['NIK' => 'MLP 002.0650.2024', 'NAMA' => 'Arjuna Delyandi Saputra', 'AREA' => 'Head Office', 'DEPT' => 'Legal Department', 'JABATAN' => 'Staff Corporate Legal'],
            ['NIK' => 'MLP 002.0655.2024', 'NAMA' => 'Tifani Indriani', 'AREA' => 'Head Office', 'DEPT' => 'FAT Department', 'JABATAN' => 'Staff TAX'],
            ['NIK' => 'MLP 002.0606.2024', 'NAMA' => 'Duwi Kurniasih', 'AREA' => 'Head Office', 'DEPT' => 'Business Analyst Department', 'JABATAN' => 'Staff Data Analyst'],
            ['NIK' => '-', 'NAMA' => 'Novia', 'AREA' => 'Head Office', 'DEPT' => 'Admin & General', 'JABATAN' => 'Personal Assistant'],
            ['NIK' => 'MLP 002.0645.2024', 'NAMA' => 'Andri Budi Setiawan', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Driver'],
            ['NIK' => 'MLP 002.0644.2024', 'NAMA' => 'Dafit Taruna', 'AREA' => 'Head Office', 'DEPT' => 'HRGA-IT Department', 'JABATAN' => 'Driver'],
        ]);
    }
}
