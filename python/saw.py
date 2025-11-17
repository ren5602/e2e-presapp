import numpy as np
import pandas as pd

class SAW:
    def __init__(self, dataframe, weights, criteria_types, jumlah_anggota):
        self.df = dataframe
        self.weights = np.array(weights)
        self.criteria = np.array(criteria_types)
        self.jumlah_anggota = jumlah_anggota
        self.alternatives = dataframe.iloc[:, 0]
        self.X = dataframe.iloc[:, 1:].values.astype(float)

    def normalize_matrix(self):
        norm_matrix = np.zeros_like(self.X, dtype=float)
        for j in range(self.X.shape[1]):
            col = self.X[:, j]
            max_val = np.nanmax(col)
            min_val = np.nanmin(col)

            if self.criteria[j] == 'benefit':
                if max_val != 0:
                    norm_matrix[:, j] = col / max_val
                else:
                    norm_matrix[:, j] = 0
            elif self.criteria[j] == 'cost':
                norm_matrix[:, j] = np.where(col != 0, min_val / col, 0)

        # Ganti NaN dan inf dengan 0 agar aman
        norm_matrix = np.nan_to_num(norm_matrix, nan=0.0, posinf=0.0, neginf=0.0)
        return norm_matrix

    def run(self):
        norm = self.normalize_matrix()
        scores = norm @ self.weights

        # Tangani kemungkinan NaN pada scores
        scores = np.nan_to_num(scores, nan=0.0, posinf=0.0, neginf=0.0)

        result = pd.DataFrame({
            "mahasiswa_id": self.alternatives,
            "score": scores,
            "rank": (-scores).argsort().argsort() + 1
        }).sort_values("rank").reset_index(drop=True)

        return result.head(self.jumlah_anggota)