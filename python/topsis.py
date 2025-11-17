import numpy as np
import pandas as pd

class Topsis:
    def __init__(self, dataframe, weights, criteria_types, jumlah_anggota):
        self.df = dataframe
        self.weights = np.array(weights)
        self.criteria = np.array(criteria_types)
        self.alternatives = dataframe.iloc[:, 0]
        self.jumlah_anggota = jumlah_anggota  # simpan sebagai integer
        self.X = dataframe.iloc[:, 1:].values

    def normalize_matrix(self):
        denominator = np.sqrt((self.X ** 2).sum(axis=0))
        denominator[denominator == 0] = 1  # Hindari pembagian dengan nol
        return self.X / denominator


    def weighted_normalized_matrix(self, norm_matrix):
        
        return norm_matrix * self.weights

    def ideal_solutions(self, V):
        ideal_pos = np.where(self.criteria == 'benefit', np.max(V, axis=0), np.min(V, axis=0))
        ideal_neg = np.where(self.criteria == 'benefit', np.min(V, axis=0), np.max(V, axis=0))
        return ideal_pos, ideal_neg

    def distance_to_ideal(self, V, ideal):
        return np.sqrt(((V - ideal) ** 2).sum(axis=1))

    def closeness_coefficient(self, D_plus, D_minus):
        denominator = D_plus + D_minus
        # Hindari pembagian 0/0 → jika 0, beri nilai C = 0
        with np.errstate(invalid='ignore', divide='ignore'):
            C = np.where(denominator == 0, 0, D_minus / denominator)
        return C


    def run(self):
        norm = self.normalize_matrix()
        V = self.weighted_normalized_matrix(norm)
        A_plus, A_minus = self.ideal_solutions(V)
        D_plus = self.distance_to_ideal(V, A_plus)
        D_minus = self.distance_to_ideal(V, A_minus)
        C = self.closeness_coefficient(D_plus, D_minus)

        # result = pd.DataFrame({
        #     "Mahasiswa": self.alternatives,
        #     "C": C,
        #     "Ranking": C.argsort()[::-1] + 1
        # }).sort_values("C", ascending=False).reset_index(drop=True)

        # Hitung ranking berdasarkan nilai C tertinggi
        # ranking = (-C).argsort().argsort() + 1

        # Buat DataFrame hasil
        result = pd.DataFrame({
            "mahasiswa_id": self.alternatives,
            "C": C,
            "rank": (-C).argsort().argsort() + 1
        }).sort_values("rank").reset_index(drop=True)

        # Ambil hanya sebanyak jumlah anggota
        result = result.head(self.jumlah_anggota)

        return result
