import numpy as np

class PSI:
    def __init__(self, matrix, criteria_type):
        """
        :param matrix: List of List (alternatif × kriteria)
        :param criteria_type: List of str ("benefit" or "cost")
        """
        self.matrix = np.array(matrix, dtype=float)
        self.criteria_type = criteria_type

    def normalize(self):
        norm_matrix = np.zeros_like(self.matrix)
        for j in range(self.matrix.shape[1]):
            col = self.matrix[:, j]
            max_val = col.max()
            min_val = col.min()

            if self.criteria_type[j] == 'benefit':
                norm_matrix[:, j] = col / max_val if max_val != 0 else 0
            else:  # cost
                norm_matrix[:, j] = min_val / col
        norm_matrix = np.nan_to_num(norm_matrix, nan=0.0, posinf=0.0, neginf=0.0)
        return norm_matrix

    def calculate_weights(self):
        norm_matrix = self.normalize()
        mean_values = norm_matrix.mean(axis=0)
        diffs = np.abs(norm_matrix - mean_values)
        sum_diffs = diffs.sum(axis=0)
        pvv = 1 - sum_diffs / self.matrix.shape[0]
        weights = pvv / np.sum(pvv)
        weights = np.nan_to_num(weights, nan=0.0)
        return weights.tolist()
